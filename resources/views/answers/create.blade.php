@extends('partials.layouts.layout')

@section('title', 'Add Questions')
@section('content')

    @include('partials.sidebar._sidebar')
    @include('partials.messages.confirmmessage')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2>Add Answer to question {{ $id }}</h2>
                </div>
                <div class="card-body">
                   

                    <form action="{{ route('answers.store', ['id' => $id, 'exam_id' => $exam_id]) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Answer</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" name="answer[]" placeholder="Please enter answer here" class="form-control">
                    <label class="form-check-label mb-0 d-flex align-items-center gap-1">
                        Correct
                        <input type="radio" name="is_correct" value="0" class="form-check-input">
                    </label>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Answer</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" name="answer[]" placeholder="Please enter answer here" class="form-control">
                    <label class="form-check-label mb-0 d-flex align-items-center gap-1">
                        Correct
                        <input type="radio" name="is_correct" value="1" class="form-check-input">
                    </label>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Answer</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" name="answer[]" placeholder="Please enter answer here" class="form-control">
                    <label class="form-check-label mb-0 d-flex align-items-center gap-1">
                        Correct
                        <input type="radio" name="is_correct" value="2" class="form-check-input">
                    </label>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Answer</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="text" name="answer[]" placeholder="Please enter answer here" class="form-control">
                    <label class="form-check-label mb-0 d-flex align-items-center gap-1">
                        Correct
                        <input type="radio" name="is_correct" value="3" class="form-check-input">
                    </label>
                </div>
            </div>
            
                <button type="submit" class="btn btn-primary">Add Answer </button>
        </form>
                </div>
                
            </div>
            
        </div>
        
    </div>
@endsection
