@extends('partials.layouts.layout')

@section('title', 'Enter Test Results for permit')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">Add Test Results</h2>
                    <hr>
                    <form method="POST" action="{{ route('test-results.permit.add') }}">
                        @method('POST')
                        @csrf
                        @foreach ($permit_applications as $permit_application)
                            <div class="mt-3">
                                <label for="" class="form-label">Permit Type</label>
                                <select name="" id="" class="form-control" readonly disabled>
                                    <option value="">Test</option>
                                    @foreach ($permit_categories as $permit_category)
                                        <option value="{{ $permit_category->id }}"
                                            {{ $permit_application->permit_category_id == $permit_category->id ? 'selected' : '' }}>
                                            {{ $permit_category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="text" name="application_type_id"
                                    value="{{ $permit_application->permit_category_id }}" hidden>
                                <input type="text" name="application_id" value="{{ $permit_application->id }}" hidden>
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
                                <input type="text" class="form-control" value="{{ $permit_application->address }}"
                                    readonly>
                            </div>
                            <div class="mt-3">
                                <div class="form-label">Business Address</div>
                                <input type="text" class="form-control"
                                    value="{{ $permit_application->employer_address }}" readonly>
                            </div>
                            <div class="mt-3">
                                <div class="form-label">Date of Birth</div>
                                <input type="date" class="form-control"
                                    value="{{ $permit_application->date_of_birth }}"readonly>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label for="" class="form-label">Home Phone</label>
                                    <input type="text" class="form-control"
                                        value="{{ $permit_application->home_phone }}" readonly>
                                </div>
                                <div class="col">
                                    <label for="" class="form-label">Work Phone</label>
                                    <input type="text" class="form-control"
                                        value="{{ $permit_application->work_phone }}" readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <label for="" class="form-label">Trainer(s)</label>
                                    <input type="text" class="form-control" name="staff_contact"
                                        value="{{ old('staff_contact') }}">
                                    @error('staff_contact')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="" class="form-label">Test Score(in %)</label>
                                    <input type="number" class="form-control" name="overall_score"
                                        value="{{ old('overall_score') }}">
                                    @error('overall_score')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <label for="" class="form-label">Test Location</label>
                                    <input type="text" class="form-control" value="{{ $permit_appointments->name }}"
                                        name="test_location" readonly>
                                </div>
                                <div class="col">
                                    <label for="" class="form-label">Test Date</label>
                                    <input type="date" class="form-control"
                                        value="{{ $permit_appointments->appointment_date }}" name="test_date" readonly>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="" class="form-label">Comments</label>
                                <textarea class="form-control" name="comments">{{ old('comments') }}</textarea>
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <a class="btn btn-danger" onclick="history.back()">
                                    Cancel
                                </a>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
