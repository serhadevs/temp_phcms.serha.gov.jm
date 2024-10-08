@extends('partials.layouts.layout')

@section('title', 'Exam Dates')
@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <main class="content px-3 py-4">
            <div class="container-fluid">
                @include('partials.messages.table_loading')
                <div class="card">
                    <h2 class="card-header">
                        <div class="row justify-content-between">
                            <div class="col">
                                <h2 class="text-muted">Exam Dates</h2>
                            </div>
                            <div class="col-auto no-wrap">
                                <div class="row">
                                    <div class="col">
                                        <a class="btn btn-success text-nowrap" href="{{ route('examdate.create') }}">
                                            Add New Date
                                        </a>
                                    </div>
                                    <div class="col">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Filter Exam Dates
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="/food-establishments/filter/0"></a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        href="/food-establishments/filter/1">Yesterday</a>
                                                </li>
                                                <li><a class="dropdown-item" href="/food-establishments/filter/7">Last
                                                        Week</a></li>
                                                <li><a class="dropdown-item" href="/food-establishments/filter/30">Last
                                                        Month</a>
                                                </li>
                                                <li><a class="dropdown-item" href="/food-establishments/filter/90">Last 3
                                                        month</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </h2>
                    <div class="card-body">
                        @include('partials.messages.messages')
                       @include('partials.tables.examdates')
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                    </div>
                </div>
            </div>
            
        </main>
    </div>

@endsection
