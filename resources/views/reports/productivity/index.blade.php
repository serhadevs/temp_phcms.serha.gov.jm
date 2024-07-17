@extends('partials.layouts.layout')

@section('title', 'Productivity Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <h2 class="card-header text-muted mb-2">Productivity Report</h2>
                <div class="card-body">
                    <form action="{{ route('reports.productivity') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col">
                                <label for="starting_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control " name="starting_date" id="starting_date">
                                @error('starting_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="col">
                                <label for="ending_date" class="form-label">End Date</label>
                                <input type="date" value = "{{ date('Y-m-d') }}" class="form-control @error('ending_date') is-invalid @enderror" name="ending_date" id="ending_date" max="{{ date('Y-m-d') }}">
                                @error('ending_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        
                        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger mt-3">Back to Dashboard</a>
                        <button class="btn btn-success mt-3" type="submit">Generate Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

