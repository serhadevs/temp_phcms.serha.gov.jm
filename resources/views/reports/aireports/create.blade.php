@extends('partials.layouts.layout')

@section('title', 'AI Generated Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <form action="{{ route('reports.generate.report') }}" method="POST">
                @csrf
                @method('POST')
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-muted">AI General Report</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <form id="ai-report-form">
                                <label for="prompt">Enter your prompt:</label>
                                <input type="text" id="prompt" class = "form-control" name="prompt" required>
                                
                            </form>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                        <button type="submit" class="btn btn-success">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
@endsection


