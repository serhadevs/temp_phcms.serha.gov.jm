@extends('partials.layouts.layout')

@section('title', 'Processed Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Create Food Est. Test Result
                    </h2>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Name</label>
                        <input type="text" class="form-control" value="{{ $application->establishment_name }}" disabled>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Category</label>
                        <input type="text" class="form-control" value="{{ $application->establishmentCategory?->name }}" disabled>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Address</label>
                        <input type="text" class="form-control" value="{{ $application->establishment_address }}" disabled>
                    </div>
                    @include('partials.forms.test_result_ests')
                </div>
            </div>
        </div>
    </div>
@endsection
