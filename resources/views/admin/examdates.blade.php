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
                                                Filter Exam Sites
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{ route('examsites.index') }}" class="dropdown-item">All</a></li>
                                                <li><a class="dropdown-item" href="{{ route('examdate.filter',['id'=>1]) }}">St Catherine</a></li>
                                                <li><a class="dropdown-item" href="{{ route('examdate.filter',['id'=>2]) }}">St Thomas</a></li>
                                                <li><a class="dropdown-item" href="{{ route('examdate.filter',['id'=>3]) }}">Kingston and St Andrew</a></li>
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
