<?php
// app/Jobs/GeneratePermitsZip.php
namespace App\Jobs;

use App\Models\EstablishmentClinics;
use App\Models\PermitApplication;
use App\Models\PermitDownload;
use App\Services\PdfGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class GeneratePermitsZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;   // max seconds a single attempt is allowed to run
    public int $tries = 3;       // how many times Laravel will attempt this job
    public int $backoff = 10;

    // Resized photo target dimensions (fits the .card-photo box in onsiteCardPdf.blade.php)
    private const PHOTO_MAX_WIDTH = 240;
    private const PHOTO_MAX_HEIGHT = 260;
    private const PHOTO_JPEG_QUALITY = 70;

    public function __construct(
        public int $establishmentId,
        public string $downloadToken
    ) {}

    public function handle(): void
    {
        ini_set('memory_limit', '512M');

        $download = PermitDownload::where('token', $this->downloadToken)->firstOrFail();
        $download->update(['status' => 'processing', 'progress' => 0]);

        try {
            $applicants = PermitApplication::with(['permitCategory', 'signOffs', 'testResults'])
                ->where('establishment_clinic_id', $this->establishmentId)
                ->where('sign_off_status', 1)
                ->get();

            if ($applicants->isEmpty()) {
                $download->update([
                    'status' => 'failed',
                    'error_message' => 'No signed-off permits found for this establishment.',
                ]);
                return;
            }

            $totalApplicants = $applicants->count();
            $applicantsData = [];

            // ---- Step 1: build QR codes + resized photo thumbnails for every applicant ----
            // Both are cached to disk, so repeat runs for the same applicant are near-instant.
            foreach ($applicants as $index => $applicant) {
                try {
                    $qrImage = $this->generateQrImage($applicant);
                    $photoBase64 = $this->getResizedPhotoBase64($applicant);

                    $applicantsData[] = [
                        'applicant' => $applicant,
                        'qrImage' => $qrImage,
                        'photoBase64' => $photoBase64,
                    ];
                } catch (\Throwable $e) {
                    Log::error('Failed to prepare permit data', [
                        'permit_no' => $applicant->permit_no ?? null,
                        'establishment_clinic_id' => $this->establishmentId,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                // QR + photo prep counts as the first 40% of progress
                $progress = (int) round((($index + 1) / $totalApplicants) * 40);
                $download->update(['progress' => $progress]);
            }

            if (empty($applicantsData)) {
                $download->update([
                    'status' => 'failed',
                    'error_message' => 'Unable to generate any permits for this establishment.',
                ]);
                return;
            }

            $download->update(['progress' => 50]);

            // ---- Step 2: render everyone into ONE combined multi-page PDF ----
            $pdfContent = PdfGenerator::renderFromView('verify.onsiteCardPdf', [
                'applicants' => $applicantsData,
            ]);

            $download->update(['progress' => 90]);

            $fileName = 'Food_Handlers_Permits_' . $this->establishmentId . '_' . now()->timestamp . '.pdf';
            $storedPath = 'permit-downloads/' . $this->downloadToken . '.pdf';
            $fullPath = storage_path('app/' . $storedPath);

            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            file_put_contents($fullPath, $pdfContent);
            unset($pdfContent);
            gc_collect_cycles();

            $download->update([
                'status' => 'ready',
                'progress' => 100,
                'file_path' => $storedPath,
                'file_name' => $fileName,
                'expires_at' => now()->addHours(2),
            ]);
        } catch (\Throwable $e) {
            Log::error('Permit PDF generation job failed', [
                'establishment_clinic_id' => $this->establishmentId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $download->update([
                'status' => 'failed',
                'error_message' => 'Something went wrong while generating the permits.',
            ]);
        }
    }

    private function generateQrImage(PermitApplication $applicant): string
    {
        return \Illuminate\Support\Facades\Cache::rememberForever(
            "qr_code_{$applicant->permit_no}",
            function () use ($applicant) {
                $qrUrl = url('/api/verify-permit/' . $applicant->permit_no);
                return base64_encode(
                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(160)->margin(1)->generate($qrUrl)
                );
            }
        );
    }

    /**
     * Resize (and cache) an applicant's photo down to a small JPEG suitable for
     * embedding in the PDF, then return it as a base64 string. This is what
     * prevents the memory exhaustion / PCRE backtrack errors caused by embedding
     * full-resolution camera photos directly.
     *
     * Uses PHP's built-in GD extension — no extra Composer dependency required.
     */
    private function getResizedPhotoBase64(PermitApplication $applicant): ?string
    {
        if (!$applicant->photo_upload) {
            return null;
        }

        $disk = Storage::disk('public');

        if (!$disk->exists($applicant->photo_upload)) {
            return null;
        }

        $cacheDir = storage_path('app/photo-thumbs');
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        $thumbPath = $cacheDir . '/' . $applicant->id . '.jpg';

        // Reuse the cached thumbnail if it already exists — only generate once ever.
        if (file_exists($thumbPath)) {
            return base64_encode(file_get_contents($thumbPath));
        }

        $originalPath = $disk->path($applicant->photo_upload);

        try {
            $resized = $this->resizeImageWithGd(
                $originalPath,
                $thumbPath,
                self::PHOTO_MAX_WIDTH,
                self::PHOTO_MAX_HEIGHT,
                self::PHOTO_JPEG_QUALITY
            );

            if (!$resized) {
                return null;
            }

            return base64_encode(file_get_contents($thumbPath));

        } catch (\Throwable $e) {
            Log::error('Failed to resize applicant photo', [
                'permit_application_id' => $applicant->id,
                'photo_upload' => $applicant->photo_upload,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Resize an image file to fit within max width/height (preserving aspect ratio),
     * re-encode as JPEG at the given quality, and save to $destPath.
     *
     * Returns true on success, false if the source image couldn't be read.
     */
    private function resizeImageWithGd(string $sourcePath, string $destPath, int $maxWidth, int $maxHeight, int $quality): bool
    {
        $imageInfo = @getimagesize($sourcePath);
        if ($imageInfo === false) {
            return false;
        }

        [$origWidth, $origHeight, $imageType] = $imageInfo;

        $source = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            IMAGETYPE_GIF => @imagecreatefromgif($sourcePath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($sourcePath) : null,
            default => null,
        };

        if (!$source) {
            return false;
        }

        // Calculate new dimensions, preserving aspect ratio, never upscaling
        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight, 1);
        $newWidth = (int) round($origWidth * $ratio);
        $newHeight = (int) round($origHeight * $ratio);

        $destImage = imagecreatetruecolor($newWidth, $newHeight);

        // Flatten transparency onto a white background (avoids black backgrounds for PNGs with alpha)
        $white = imagecolorallocate($destImage, 255, 255, 255);
        imagefill($destImage, 0, 0, $white);

        imagecopyresampled(
            $destImage, $source,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $origWidth, $origHeight
        );

        $saved = imagejpeg($destImage, $destPath, $quality);

        imagedestroy($source);
        imagedestroy($destImage);

        return $saved;
    }
}