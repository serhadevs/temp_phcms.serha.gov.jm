<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AnswersController extends Controller
{
    public function create($id,$exam_id){

        return view('answers.create',compact('id','exam_id'));
    }

    public function store(Request $request, $id,$exam_id)
    {
        $request->validate([
            'answer.*' => 'required',
            'is_correct' => 'required',
        ]);

        //dd($request->id);
    
        // Get all the answers from the form
        $answers = $request->input('answer');
        $correctAnswer = $request->input('is_correct');
        //$examId = $request->id;

        //check to see if the question already has answers
        $existingAnswers = Answers::where('question_id', $id)->get();
        if ($existingAnswers->isNotEmpty()) {
            return redirect()->route('questions.view', ['id' => $id])->with('error', 'Answers already exist for this question.');
        }
        // Loop through the answers and save them to the database
        
        foreach ($answers as $key => $answerText) {
            // Create new answer
            $answer = new Answers();
            $answer->answer = $answerText;
            $answer->question_id = $id;
            //If is_correct = 1, then set is_correct to 1, else set to 0
            $answer->is_correct = ($key == $correctAnswer) ? 1 : 0;
            $answer->exam_id = $exam_id;
            $answer->user_id = auth()->user()->id;
            $answer->save();
        }
    
        return redirect()->route('questions.view', ['id' => $exam_id])->with('success', 'Answers added successfully.');
    }
}
