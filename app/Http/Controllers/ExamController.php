<?php

namespace App\Http\Controllers;

use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExamController extends Controller
{

    public function index(){
        $exams = StudentExam::all();
        return view('exams.index', compact('exams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        //Check to see if there is an exam with the same title
        $existingExam = StudentExam::where('title', $request->input('title'))
            ->where('user_id', auth()->id()) // Only allow the user to create exams with unique titles
            ->first();
        
        //If the exam already exists, return an error
        if ($existingExam) {
            return redirect()->back()->with('error','An exam with this title already exists.');
        }

        $exam = new StudentExam();
        $exam->title = $request->input('title');
        $exam->user_id = auth()->id(); 
        $exam->save();

        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
    }
    public function review($examId)
    {
        $studentExam = StudentExam::with(['questions', 'answers'])
            ->where('id', $examId)
            ->where('user_id', auth()->id()) // Only allow review of exams owned by the user
            ->firstOrFail();

        // Assuming 'answers' is a relation that links each question to the user's answer
        $reviewData = $studentExam->questions->map(function ($question) use ($studentExam) {
            $userAnswer = optional($studentExam->answers->where('question_id', $question->id)->first())->selected_option;

            return [
                'question'       => $question->question,
                'options'        => $question->options,
                'correct_answer' => $question->correct_answer,
                'user_answer'    => $userAnswer,
                'is_correct'     => $userAnswer === $question->correct_answer,
            ];
        });

        return view('exam.review', [
            'studentExam' => $studentExam,
            'reviewData' => $reviewData,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $exam = StudentExam::where('id', $request->input('exam_id'))
            ->where('user_id', auth()->id()) // Only allow the user to update their own exams
            ->firstOrFail();

        $exam->title = $request->input('title');
        $exam->save();

        return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
    }
}
