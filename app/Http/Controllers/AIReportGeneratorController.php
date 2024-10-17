<?php

namespace App\Http\Controllers;

use App\Models\PermitApplication;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use OpenAI\Laravel\Facades\OpenAI;

class AIReportGeneratorController extends Controller
{


    public function index(){
        return view('reports.aireports.create');
    }

    public function generateReport(Request $request){
        $prompt = $request->prompt;

        $data = PermitApplication::whereDate('created_at','>=','2024-01-01')->get();

        //dd($data);
        $formattedData = $this->formatDataForPrompt($data);

        $aiPrompt = "Based on the following data: " . $formattedData . ", " . $prompt;

        $response = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'As a Data Analysis '],
                ['role' => 'user', 'content' => $prompt],
            ],
            'max_tokens' => 1000,
        ]);

        $report = $response['choices'][0]['message']['content'];

        dd($report);

        return view('reports.aireports.report',compact([
            'report' => $report
        ]));

    }

    private function formatDataForPrompt($data)
    {
        $formatted = '';
        foreach ($data as $item) {
            $formatted .= $item->column1 . ', ' . $item->column2 . '... '; // Adjust based on your data
        }
        return $formatted;
    }
}
