@extends('partials.layouts.layout')

@section('title', 'Processed Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <h4 class="card-header">
                    Create Food Establishment Inspection Results
                </h4>
                <div class="card-body">
                    <div class="mt-3">
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
                    <form action="{{ route('test-results.food-est.store') }}" method="POST">
                        @method('POST')
                        @csrf
                        @include('partials.forms.test_result_ests')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
