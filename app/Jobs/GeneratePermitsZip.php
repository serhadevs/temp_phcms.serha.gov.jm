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

    public int $timeout = 300; // 5 minutes — safe now since it's off the web request entirely
    public int $tries = 2;

    public function __construct(
        public int $establishmentId,
        public string $downloadToken
    ) {}

    public function handle(): void
    {
        $download = PermitDownload::where('token', $this->downloadToken)->firstOrFail();
        $download->update(['status' => 'processing']);

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

            $applicantsData = [];

            foreach ($applicants as $applicant) {
                try {
                    $qrImage = $this->generateQrImage($applicant);
                    $applicantsData[] = ['applicant' => $applicant, 'qrImage' => $qrImage];
                } catch (\Throwable $e) {
                    Log::error('Failed to generate QR code for permit', [
                        'permit_no' => $applicant->permit_no ?? null,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }
            }

            if (empty($applicantsData)) {
                $download->update([
                    'status' => 'failed',
                    'error_message' => 'Unable to generate any permits for this establishment.',
                ]);
                return;
            }

            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Render once
            $pdf = Pdf::loadView('verify.onsiteCardPdf', ['applicants' => $applicantsData])->setPaper('A4');
            $combinedPath = $tempDir . '/combined_' . $this->downloadToken . '.pdf';
            file_put_contents($combinedPath, $pdf->output());

            // Split + zip
            $zipFileName = 'Food_Handlers_Permits_' . $this->establishmentId . '_' . now()->timestamp . '.zip';
            $storedZipPath = 'permit-downloads/' . $this->downloadToken . '.zip';
            $fullZipPath = storage_path('app/' . $storedZipPath);

            if (!file_exists(dirname($fullZipPath))) {
                mkdir(dirname($fullZipPath), 0755, true);
            }

            $zip = new ZipArchive();
            $zip->open($fullZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            $sourcePageCount = (new Fpdi())->setSourceFile($combinedPath);

            foreach ($applicantsData as $index => $data) {
                $pageNumber = $index + 1;
                if ($pageNumber > $sourcePageCount) break;

                $splitPdf = new Fpdi();
                $splitPdf->setSourceFile($combinedPath);
                $template = $splitPdf->importPage($pageNumber);
                $size = $splitPdf->getTemplateSize($template);
                $splitPdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $splitPdf->useTemplate($template);

                $permitNo = $data['applicant']->permit_no ?? "permit_{$pageNumber}";
                $zip->addFromString("Food_Handlers_Permit_{$permitNo}.pdf", $splitPdf->Output('S'));
            }

            $zip->close();
            @unlink($combinedPath);

            $download->update([
                'status' => 'ready',
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

