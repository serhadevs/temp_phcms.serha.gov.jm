@extends('partials.layouts.layout')

@section('title', 'General Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">

            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">Generated General Report</h2>
                </div>
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
@endsection
