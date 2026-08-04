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

            $totalApplicants = $applicants->count();
            $applicantsData = [];

            // ---- Step 1: build QR codes for every applicant (cached, so repeat runs are fast) ----
            foreach ($applicants as $index => $applicant) {
                try {
                    $qrImage = $this->generateQrImage($applicant);
                    $applicantsData[] = [
                        'applicant' => $applicant,
                        'qrImage' => $qrImage,
                    ];
                } catch (\Throwable $e) {
                    Log::error('Failed to generate QR code for permit', [
                        'permit_no' => $applicant->permit_no ?? null,
                        'establishment_clinic_id' => $this->establishmentId,
                        'error' => $e->getMessage(),
                    ]);
                    continue;
                }

                // QR generation counts as the first 40% of progress
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
}