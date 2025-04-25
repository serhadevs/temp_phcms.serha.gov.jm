@extends('partials.layouts.layout')

@section('title', 'View Questions')
@section('content')

    @include('partials.sidebar._sidebar')
    @include('partials.messages.confirmmessage')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2>View Questions for Exam Id {{ $id }}</h2>
                </div>
                <div class="card-body">
                   
                    @include('partials.tables.questions')
                </div>
                <div class="card-footer">
                    <a href="{{ route('exams.index') }}" class="btn btn-danger">Back to Questions</a>
                </div>
                
            </div>
            
        </div>
        
    </div>
@endsection
