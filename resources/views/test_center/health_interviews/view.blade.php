@extends('partials.layouts.layout')

@section('title', 'View Health Interview')

@section('body')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <h2 class="text-muted">View for {{ $application->firstname.' '.$application->lastname }}</h2>
                <hr>
                <div class="mt-3">
                    
                </div>
            </div>
        </div>
    </div>
@endsection