<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Questions;
use App\Models\StudentExam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::channel('systemOperations')->info('Fetching exam question list', ['user_id' => auth()->user()->id]);
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($examId)
    {
        Log::channel('systemOperations')->info('Loading exam question create form', ['user_id' => auth()->user()->id, 'examId' => $examId]);
        $exam = StudentExam::findOrFail($examId);
        return view('questions.create', compact('exam'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,$examId)
    {
        Log::channel('systemOperations')->info('Creating exam question', ['user_id' => auth()->user()->id, 'examId' => $examId]);
        $request->validate([
            'question' => 'required|string',
        ]);
    
        Questions::create([
            'exam_id' => $examId,
            'question' => $request->question,
            'user_id' => auth()->user()->id,
        ]);
    
        return redirect()->route('exams.index', $examId)->with('success', 'Question added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::channel('systemOperations')->info('Viewing exam question', ['user_id' => auth()->user()->id, 'id' => $id]);
        $questions = Questions::with('answers')->where('exam_id', $id)->get();
        //dd($questions);
        return view('questions.view', compact('questions', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::channel('systemOperations')->info('Loading exam question edit form', ['user_id' => auth()->user()->id, 'id' => $id]);
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::channel('systemOperations')->info('Updating exam question', ['user_id' => auth()->user()->id, 'id' => $id]);
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::channel('systemOperations')->info('Deleting exam question', ['user_id' => auth()->user()->id, 'id' => $id]);
        //
    }
}
