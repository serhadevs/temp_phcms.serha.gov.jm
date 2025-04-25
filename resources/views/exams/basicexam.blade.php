@extends('partials.layouts.layout')

@section('title', 'Exam')
@section('content')


    @include('partials.sidebar._sidebar')
    @include('partials.messages.confirmmessage')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2>Questions </h2>
                </div>
                <div class="card-body">
                    <p>Firstname: {{ $applicant->firstname }}</p>
                    <p>Lastname: {{ $applicant->lastname }}</p>
                    <p>Exam Id: {{ $exam_id }}</p>

                    <form action="{{ route('questions.take-exam.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="text" name = "exam_id" value="{{ $exam_id }}">
                        <input type="text" name="app_id" value="{{ $applicant->id }}">
                        @foreach($questions->questions as $index => $question)
                        <div class="question-container mb-4 p-3 border rounded">
                            <div class="question-header d-flex">
                                <span class="question-number me-2">{{ $index + 1 }}.</span>
                                <div class="question-text">{{ $question->question }}</div>
                            </div>
                            
                            @if($question->answers->isNotEmpty())
                            <div class="answers-container mt-3">
                                @foreach($question->answers as $answerIndex => $answer)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="question_{{ $question->id }}" 
                                        id="q{{ $question->id }}_a{{ $answerIndex }}" value="{{ $answer->id }}">
                                    <label class="form-check-label" for="q{{ $question->id }}_a{{ $answerIndex }}">
                                        {{ chr(65 + $answerIndex) }}. {{ $answer->answer }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="alert alert-warning mt-2">No answers available for this question.</div>
                            @endif
                        </div>
                        @endforeach
        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="submit" class="btn btn-primary">Submit Answers</button>
                        </div>
                    </form>
                </div>

            </div>


        </div>

    @endsection
