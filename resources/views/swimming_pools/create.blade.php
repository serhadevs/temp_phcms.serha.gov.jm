@extends('partials.layouts.layout')

@section('title', 'Create New Swimming Pool')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-header text-muted">
                    <h2 class="text-muted">
                        Create New Swimming Pool
                    </h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('swimming-pools.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        @include('partials.forms.swimming_pool_app_form')
                       
                   
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard')}}" class="btn btn-danger">Back to Dashboard</a>
                    <button class="btn btn-primary" type="button" onclick="showLoading(this)">
                        Submit Application
                    </button>
                </div>
            </form>
            </div>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
