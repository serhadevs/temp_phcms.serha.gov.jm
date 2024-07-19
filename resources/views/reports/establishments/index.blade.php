@extends('partials.layouts.layout')

@section('title', 'Applications By Category Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <h2 class="card-header text-muted mb-2">Applications By Category</h2>
                <div class="card-body">
                    <form action="{{ route('reports.appcount') }}" method="POST">
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

                        <div class="col mt-2">
                            <label for="selectCategory" class="form-label">Module Type</label>
                            <select name="module" id="selectCategory"
                                class="form-control @error('module') is-invalid @enderror">
                                <option value="0">Select an option</option>
                                <option value="1">Food Handlers Permit</option>
                                <option value="2">Establishment Licenses</option>
                            </select>

                        </div>
                        @error('module')
                            <p class="text-danger">{{ $message }}</div>
                        @enderror

                        
                        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger mt-3">Back to Dashboard</a>
                        <button class="btn btn-success mt-3" type="submit">Generate Report</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


