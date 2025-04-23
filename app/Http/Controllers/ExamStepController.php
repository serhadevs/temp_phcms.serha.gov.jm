<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use App\Models\StudentExam;
use Illuminate\Http\Request;

class ExamStepController extends Controller
{
    public function showStep(Request $request, $examId, $step)
{
    $exam = StudentExam::with('questions')->findOrFail($examId);
    $question = $exam->questions->slice($step - 1, 1)->first();

    if (!$question) {
        return redirect()->route('exam.review', $examId);
    }

    return view('exam.step', compact('question', 'step', 'exam'));
}

public function storeAnswer(Request $request, $examId, $step)
{
    $request->validate([
        'answer' => 'required|string'
    ]);

    Answers::updateOrCreate(
        [
            'user_id' => auth()->id(),
            'exam_id' => $examId,
            'question_id' => $request->input('question_id')
        ],
        ['selected_answer' => $request->input('answer')]
    );

    return redirect()->route('exam.step', ['examId' => $examId, 'step' => $step + 1]);
}

}
