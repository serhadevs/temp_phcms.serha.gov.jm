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

        $mpdf->WriteHTML($html);

        return $mpdf->Output('', Destination::STRING_RETURN);
    }
}