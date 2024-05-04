@extends('partials.layouts.layout')

@section('title', 'Edit Barber/Cosmet Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Edit Barber/Cosmet Test Results {{ $application->firstname }} {{ $application->lastname }}
                    </h2>
                    <hr>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">First Name</label>
                            <input type="text" class="form-control" disabled value="{{ $application->firstname }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" disabled value="{{ $application->middlename }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Last Name</label>
                            <input type="text" class="form-control" disabled value="{{ $application->lastname }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Home Address</label>
                        <input type="text" class="form-control" disabled value="{{ $application->address }}">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Business Address</label>
                        <input type="text" class="form-control" disabled value="{{ $application->employer_address }}">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Date of Birth</label>
                        <input type="text" class="form-control" disabled value="{{ $application->date_of_birth }}">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">
                            Telephone
                        </label>
                        <input type="text" class="form-control" disabled value="{{ $application->telephone }}">
                    </div>
                    <form
                        action="{{ route('test-results.barber-cosmet.update', ['id' => $application->testResults?->id]) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        @include('partials.forms.barber_cosmet_test_results')
                        <button class="btn btn-primary mt-4" type="submit">
                            Update Results
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
