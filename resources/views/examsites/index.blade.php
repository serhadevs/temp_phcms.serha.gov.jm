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
                            
                            <div class="col-auto">
                                <div class="dropdown">
                                    <a href={{ route('examsites.create') }} class="btn btn-success">Create Exam Site</a>
                                    @if(in_array(auth()->user()->role_id,[1]))
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Filter Examsites
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ route('examsites.index') }}" class="dropdown-item">All</a></li>
                                        <li><a class="dropdown-item" href="{{ route('examsite.filter',['id'=>1]) }}">St Catherine</a></li>
                                        <li><a class="dropdown-item" href="{{ route('examsite.filter',['id'=>2]) }}">St Thomas</a></li>
                                        <li><a class="dropdown-item" href="{{ route('examsite.filter',['id'=>3]) }}">Kingston and St Andrew</a></li>
                                        </li>
                                        
                                    </ul>
                                    @endif
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
