@extends('partials.layouts.layout')

@section('title', 'Outstanding Food Handlers Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="row justify-content-between mb-3">
                <div class="col">
                    <h2>
                        Outstanding Food Handlers Results
                    </h2>
                </div>
                <div class="col-auto no-wrap">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop">
                                Create New Results
                            </button>
                        </div>
                        <div class="col">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Filter Applications
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/test-results/permit/filter/0">Today</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/test-results/permit/filter/1">Yesterday</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/test-results/permit/filter/7">Last
                                            Week</a></li>
                                    <li><a class="dropdown-item" href="/test-results/permit/filter/30">Last
                                            Month</a>
                                    </li>
                                    <li><a class="dropdown-item" href="/test-results/permit/filter/90">Last 3
                                            month</a>
                                    </li>
                                    <li><button class="dropdown-item" href="#"
                                            onclick="showSearchBar()">Custom</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form action="{{ route('test-results.permit.filter.custom') }}" method="POST">
                @csrf
                @method('POST')
                <div class="row text-center justify-content-md-center" id="search-row" style="display:none">
                    <div class="col col-md-3">
                        <input type="date" class="form-control" placeholder="Starting Date" name="starting_date"
                            id="starting_date" value="{{ old('starting_date') }}">
                        <input type="text" class="form-control" id="interval" name="interval" style="display:none"
                            hidden>
                        @error('starting_date')
                            <p class="fw-bold text-danger errors">{{ $message }}</p>
                        @enderror
                        @error('interval')
                            <p class="fw-bold text-danger errors">Interval must be 6 months or less</p>
                        @enderror
                    </div>
                    To
                    <div class="col col-md-3">
                        <input type="date" class="form-control" placeholder="Ending Date" name="ending_date"
                            id="ending_date" value="{{ old('ending_date') }}">
                        @error('ending_date')
                            <p class="fw-bold text-danger errors">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col col-md-1">
                        <button class="btn btn-md btn-success" type="submit">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
            @include('partials.tables.permit_outstanding_test_results_table')
        </div>
    </div>
@endsection
