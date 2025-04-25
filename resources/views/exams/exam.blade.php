@extends('partials.layouts.layout')

@section('title', 'Take Exam')
@section('content')


    @include('partials.sidebar._sidebar')
    @include('partials.messages.confirmmessage')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2>Take the {{ $questions->title }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('questions.start-exam') }}" method="POST">
                        @csrf
                        @method('POST')
                        <input type="text" placeholder="Enter Application Id" name="app_id" class="form-control">
                        <input type="text" placeholder="" name="exam_id" value="{{ $questions->id }}">
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
                </form>
            </div>


        </div>

    @endsection
