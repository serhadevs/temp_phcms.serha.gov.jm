@extends('partials.layouts.layout')

@section('title', 'Edit Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">
                        Food Est. Results {{ $application->establishment_name }}
                    </h2>
                </div>
                <div class="card-body">
                    <div class="">
                        <label for="" class="form-label">Establishment Name</label>
                        <input type="text" class="form-control" value="{{ $application->establishment_name }}" disabled>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Category</label>
                        <input type="text" class="form-control" value="{{ $application->establishmentCategory?->name }}"
                            disabled>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Address</label>
                        <input type="text" class="form-control" value="{{ $application->establishment_address }}"
                            disabled>
                    </div>
                    @include('partials.forms.test_result_ests')

                </div>
                <div class="card-footer">
                    <a href="/test-results/food-establishments/filter/0" class="btn btn-danger">Dashboard</a>
                    <a href="/test-results/food-establishments/edit/{{ $application->id }}" class="btn btn-warning">
                        Edit Results
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
