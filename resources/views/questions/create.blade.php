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
                    <h2>Add Question to: {{ $exam->title }}</h2>
                </div>
                <div class="card-body">
                   

        <form action="{{ route('questions.store', $exam->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <textarea name="question" class="form-control" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Add Question</button>
        </form>
                </div>
                
            </div>
            
        </div>
        
    </div>
@endsection
