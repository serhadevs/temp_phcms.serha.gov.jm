@extends('partials.layouts.layout')

@section('title', 'Collected Cards')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Collected Card
                    </h2>
                   
                </div>
            </div>
            
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
