<?php
// app/Jobs/GeneratePermitsZip.php
namespace App\Jobs;

use App\Models\EstablishmentClinics;
use App\Models\PermitApplication;
use App\Models\PermitDownload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use setasign\Fpdi\Fpdi;
use ZipArchive;

class GeneratePermitsZip implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;   // max seconds a single attempt is allowed to run
    public int $tries = 3;       // how many times Laravel will attempt this job
    public int $backoff = 10;

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

            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            $zipFileName = 'Food_Handlers_Permits_' . $this->establishmentId . '_' . now()->timestamp . '.zip';
            $storedZipPath = 'permit-downloads/' . $this->downloadToken . '.zip';
            $fullZipPath = storage_path('app/' . $storedZipPath);

            if (!file_exists(dirname($fullZipPath))) {
                mkdir(dirname($fullZipPath), 0755, true);
            }

            $zip = new ZipArchive();
            $zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $totalApplicants = $applicants->count();
            $addedCount = 0;

            foreach ($applicants as $index => $applicant) {
                try {
                    $qrImage = $this->generateQrImage($applicant);

                    // Render just THIS applicant — one small PDF at a time
                    $pdf = Pdf::loadView('verify.onsiteCardPdf', [
                        'applicants' => [
                            ['applicant' => $applicant, 'qrImage' => $qrImage],
                        ],
                    ])->setPaper('A4');

                    $permitNo = $applicant->permit_no ?? "permit_{$applicant->id}";
                    $zip->addFromString("Food_Handlers_Permit_{$permitNo}.pdf", $pdf->output());
                    $addedCount++;

                    // Explicitly release memory before the next iteration
                    unset($pdf, $qrImage);
                    gc_collect_cycles();
                } catch (\Throwable $e) {
                    Log::error('Failed to generate individual PDF for permit', [
                        'permit_no' => $applicant->permit_no ?? null,
                        'establishment_clinic_id' => $this->establishmentId,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                $progress = (int) round((($index + 1) / $totalApplicants) * 100);
                $download->update(['progress' => min($progress, 99)]);
            }

            $zip->close();

            if ($addedCount === 0) {
                @unlink($fullZipPath);
                $download->update([
                    'status' => 'failed',
                    'error_message' => 'Unable to generate any permits for this establishment.',
                ]);
                return;
            }

            $download->update([
                'status' => 'ready',
                'progress' => 100,
                'file_path' => $storedZipPath,
                'file_name' => $zipFileName,
                'expires_at' => now()->addHours(2),
            ]);
        } catch (\Throwable $e) {
            Log::error('Permit ZIP generation job failed', [
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
}
