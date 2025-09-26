@extends('partials.layouts.layout')

@section('title', 'Processed Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <h2 class="card-header text-muted">
                    Create Food Establishment Inspection Results
                </h2>
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
                    <form action="{{ route('test-results.food-est.store') }}" method="POST">
                        @method('POST')
                        @csrf
                        @include('partials.forms.test_result_ests')
                        <a href="{{ strpos(URL::previous(), 'advance-search/show') != false ? '/advance-search/create' : '/test-results/food-establishments/outstanding/filter/0' }}"
                            class="btn btn-danger mt-3">Back</a>
                        <button class="btn btn-primary mt-3" type="button" onclick="showLoading(this)">
                            Submit Results
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
