<?php

namespace App\Http\Controllers;

use App\Models\PermitApplication;
use App\Models\Questions;
use App\Models\StudentExam;
use App\Models\StudentResponses;
use App\Models\TestResult;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExamController extends Controller
{

    public function index()
    {
        $exams = StudentExam::with('questions')

            ->get();
        //dd($exams);
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
            return redirect()->back()->with('error', 'An exam with this title already exists.');
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

    // public function completeExam()
    // {
    //     //Find the applicant with a given id
    //     $applicant = PermitApplication::where('id', $id)->first();
    //     //Check if the applicant exists
    //     if (!$applicant) {
    //         return redirect()->back()->with('error', 'Applicant not found.');
    //     }
    //     //Check if the applicant has already completed the exam
    //     $existingExam = TestResult::where('application_id', $id)
    //         ->first();
    //     if ($existingExam) {
    //         return redirect()->back()->with('error', 'Applicant has already completed the exam.');
    //     }

    //     //send the questions to the view
    //     $questions = StudentExam::with('questions')->where('id', 1)->get();
    //     //dd($questions);
    //     return view('exams.exam', compact('id', 'questions'));
    // }

    public function takeExam($id){
        //Look up exam based on the id
        $questions = StudentExam::with('questions')->where('id', $id)->first();
       //dd($questions);
        //Check if the exam exists
        if (!$questions) {
            return redirect()->back()->with('error', 'Exam not found.');
        }
        return view('exams.exam', compact('questions'));
    }

    

    public function startExam(Request $request){
        $validatedInput = $request->validate([
            'app_id' => 'required|integer',
        ]);

        $exam_id = $request->input('exam_id');

        //if payment is null throw an error 
        $applicant = PermitApplication::with('payment')->where('id',$validatedInput['app_id'])->first();

        //dd($applicant);
        if (!$applicant || !$applicant->payment) {
            return redirect()->back()->with('error','You can not take the exam without making a payment');
        }

        //Find the exam with the given id
        $exam = StudentExam::where('id',$exam_id)->first();
        //Check if the exam exists
        if (!$exam) {
            return redirect()->back()->with('error','Exam not found');
        }
        //Check if the applicant has already completed the exam
        $existingExam = TestResult::where('application_id', $applicant->id)
            ->first();

        if ($existingExam) {
            return redirect()->back()->with('error','Applicant has already completed the exam.');
        }

        //Send the questions to the view
        $questions = StudentExam::with('questions')->where('id', $exam_id)->first();
        //Check if the questions exist
        if (!$questions) {
            return redirect()->back()->with('error','Questions not found');
        }
        //dd($questions);

        return view('exams.basicexam',compact('applicant','exam_id','questions'));
        // Check if the applicant has already completed the exam
    }

    public function submitExam(Request $request){

        //Get all the questions from the db
        $questions = Questions::with('answers')->where('exam_id', $request->input('exam_id'))->get();
        //Find the applicant with a given id
        $applicant = PermitApplication::where('id', $request->input('app_id'))->first();

        //Check to see if the applicant has already completed the exam
        $existingExam = TestResult::where('application_id', $applicant->id)
            ->first();
            
        if ($existingExam) {
            return redirect()->back()->with('error', 'Applicant has already completed the exam.');
        }
        
        //dd($questions[0]->answers[0]->is_correct);
         // Initialize counters
         $totalQuestions = $questions->count();
         $correctAnswers = 0;
         $answeredQuestions = 0;

         // Loop through the questions and check the answers
         foreach ($questions as $question) {
            // Get the user's answer ID from the form
            $userAnswerId = $request->input('question_' . $question->id);
            
            // Check if the question was answered
            if ($userAnswerId !== null) {
                $answeredQuestions++;
                
                // Find the correct answer for this question
                $correctAnswerId = null;
                foreach ($question->answers as $answer) {
                    if ($answer->is_correct == 1) {
                        $correctAnswerId = $answer->id;
                        break;
                    }
                }
                
                // Check if the user selected the correct answer
                if ($userAnswerId == $correctAnswerId) {
                    $correctAnswers++;
                }
            }
        }
            // Check if all questions were answered
            if ($answeredQuestions < $totalQuestions) {
                return redirect()->back()->with('error', 'Please answer all questions before submitting the exam.');
            }

            // Calculate the score
            $score = ($correctAnswers / $totalQuestions) * 100;

            //
            // Store the results in the TestResult table
            TestResult::create([
                'application_type_id' => $applicant->permit_category_id,
                'application_id' => $applicant->id,
                'test_location' => 'Online',
                'staff_contact' => 'System',
                'test_date' => Carbon::now(),
                'overall_score' => $score,
                'user_id' => auth()->user()->id,
                'facility_id' => 3,

            ]);
            
            return redirect()->route('exams.index')->with('success', 'Exam submitted successfully. Your score is: ' . $score . '%');



    }
}
