<?php

namespace App\Services;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PdfGenerator
{
    /**
     * Render a Blade view to raw PDF bytes using mPDF.
     */
    public static function renderFromView(string $view, array $data = []): string
    {
        $html = view($view, $data)->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'tempDir' => storage_path('app/mpdf-tmp'),
            'margin_top' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10,
            'margin_right' => 10,
        ]);

        $mpdf->SetDefaultBodyCSS('border', '0');

        $mpdf->WriteHTML($html);

        $pageCount = $mpdf->page;
        for ($i = 1; $i <= $pageCount; $i++) {
            $mpdf->page = $i;
            $mpdf->SetLineWidth(1); // ~3px equivalent in mPDF's unit system
            $mpdf->SetDrawColor(11, 78, 162); // #0b4ea2 in RGB
            $mpdf->Rect(4, 4, $mpdf->w - 8, $mpdf->h - 8);
        }

        return $mpdf->Output('', Destination::STRING_RETURN);
    }
}
