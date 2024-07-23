@extends('partials.layouts.layout')

@section('title', 'Summary Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
           
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted mb-2">Summary Report</h2>
                </div>
                <div class="card-body">
                    <form action={{ route('report.summary.show') }} method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">
                                    Start Date
                                </label>
                                <input type="date" class="form-control" name="starting_date">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    End Date
                                </label>
                                <input type="date" class="form-control" name="ending_date" max="date">
                            </div>
                        </div>
                        
                    
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                    <button class="btn btn-success" type="submit">
                        Generate Report
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>
@endsection
