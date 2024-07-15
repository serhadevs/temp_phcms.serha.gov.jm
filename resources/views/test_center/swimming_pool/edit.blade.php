@extends('partials.layouts.layout')

@section('title', 'Edit Swimming Pool Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <h2 class="card-header text-muted">
                        Edit {{ $application->firstname . ' ' . $application->lastname }} Swimming Pool Results
                    </h2>
                <div class="card-body">
                    <div class="row">
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
                        <label for="" class="form-label">Swimming Pool Address</label>
                        <input type="text" class="form-control" disabled
                            value="{{ $application->swimming_pool_address }}">
                    </div>
                    <form
                        action="{{ route('test-results.swimming-pools.update', ['id' => $application->testResults?->id]) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        @include('partials.forms.swimming_pool_tresults_form')
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Reason for edit
                            </label>
                            <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-primary mt-4" type="submit">
                            Update Test Results
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
