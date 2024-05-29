@extends('partials.layouts.layout')

@section('title', 'Edit Food Handlers Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">Edit Test Results
                        {{ $permit_application->firstname . '' . $permit_application->lastname }}</h2>
                    <hr>
                    <form method="POST"
                        action="{{ route('test-results.permit.update', ['id' => $permit_application->testResults?->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="mt-3">
                            <label for="" class="form-label">Permit Type</label>
                            <select name="" id="" class="form-select" readonly disabled>
                                <option value="">Test</option>
                                @foreach ($permit_categories as $permit_category)
                                    <option value="{{ $permit_category->id }}"
                                        {{ $permit_application->permit_category_id == $permit_category->id ? 'selected' : '' }}>
                                        {{ $permit_category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Photo</label>
                            <img src="{{ asset('storage/' . $permit_application->photo_upload) }}" alt="No Image found"
                                style="display:block; width:30%; height:30vh">
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">First Name</label>
                                <input type="text" class="form-control" value="{{ $permit_application->firstname }}"
                                    readonly>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" value="{{ $permit_application->middlename }}"
                                    readonly>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Last Name</label>
                                <input type="text" class="form-control" value="{{ $permit_application->lastname }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="form-label">Home Address</div>
                            <input type="text" class="form-control" value="{{ $permit_application->address }}" readonly>
                        </div>
                        <div class="mt-3">
                            <div class="form-label">Business Address</div>
                            <input type="text" class="form-control" value="{{ $permit_application->employer_address }}"
                                readonly>
                        </div>
                        <div class="mt-3">
                            <div class="form-label">Date of Birth</div>
                            <input type="date" class="form-control"
                                value="{{ $permit_application->date_of_birth }}"readonly>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Home Phone</label>
                                <input type="text" class="form-control" value="{{ $permit_application->home_phone }}"
                                    readonly>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Work Phone</label>
                                <input type="text" class="form-control" value="{{ $permit_application->work_phone }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Trainer(s)</label>
                                <input type="text" class="form-control" name="staff_contact"
                                    value="{{ old('staff_contact') ? old('staff_contact') : $permit_application->testResults?->staff_contact }}">
                                @error('staff_contact')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Test Score(in %)</label>
                                <input type="number" class="form-control" name="overall_score"
                                    value="{{ old('overall_score') ? old('overall_score') : $permit_application->testResults?->overall_score }}">
                                @error('overall_score')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">Test Location</label>
                                <input type="text" class="form-control"
                                    value="{{ empty($permit_application->establishmentClinics) ? $permit_application->appointment->first()?->examDate?->examSites?->name : $permit_application->establishmentClinics?->address }}"
                                    name="test_location" readonly>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Test Date</label>
                                <input type="date" class="form-control"
                                    value="{{ empty($permit_application->establishmentClinics) ? $permit_application->appointment->first()?->appointment_date : $permit_application->establishmentClinics?->proposed_date }}"
                                    name="test_date" readonly>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Comments</label>
                            <textarea class="form-control" name="comments">{{ old('comments') ? old('comments') : $permit_application->testResults?->comments }}</textarea>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Reason for edit</label>
                                <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-4">
                            <button type="button" class="btn btn-primary" onclick="showLoading(this)">
                                Update Test Results
                            </button>
                            <a class="btn btn-danger" onclick="history.back()">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
