@extends('partials.layouts.layout')

@section('title', 'Exam Sites')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            @include('partials.messages.messages')
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between">
                        <div class="col">
                            <h2 class="text-muted">
                                Exam Sites
                            </h2>
                        </div>
                        <div class="col-auto no-wrap">
                            <div class="row">
                                <div class="col">
                                    <a href="{{ route('examsites.create') }}" class="btn btn-success">
                                        Add Exam Site
                                    </a>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('partials.tables.examsites')
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                </div>
            </div>
        </div>
       
    </div>
@endsection
