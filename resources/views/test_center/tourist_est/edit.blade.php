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
                        Edit Tourist Inspection Result for {{ $application->establishment_name }}
                    </h2>
                    <hr>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Establishment Name</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $application->establishment_name }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Office First Name</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $application->officer_firstname }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Office Last Name</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $application->officer_lastname }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Address</label>
                        <input type="text" class="form-control" disabled
                            value="{{ $application->establishment_address }}">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Bed Capacity</label>
                        <input type="text" class="form-control" value="{{ $application->bed_capacity }}" disabled>
                    </div>
                    <form
                        action="{{ route('test-results.tourist-establishments.update', ['id' => $application->testResults?->id]) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        @include('partials.forms.test_results_tourist_est_form')
                        <button class="btn btn-primary mt-4" type="submit">
                            Update Test Results
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
