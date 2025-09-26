@extends('partials.layouts.layout')

@section('title', 'Edit Food Handlers Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid">
            <div class="card">
                <h2 class="text-muted card-header">{{ isset($is_view) ? 'View' : 'Edit' }} Test Results
                    {{ $permit_application->firstname . '' . $permit_application->lastname }}</h2>
                <div class="card-body">
                    <form method="POST"
                        action="{{ route('test-results.permit.update', ['id' => $permit_application->testResults?->id]) }}">
                        @method('PUT')
                        @csrf
                        <div class="">
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
                                <input type="text" class="form-control editable-fields" name="staff_contact"
                                    {{ isset($is_view) ? 'disabled' : '' }}
                                    value="{{ old('staff_contact') ? old('staff_contact') : $permit_application->testResults?->staff_contact }}">
                                @error('staff_contact')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Test Score(in %)</label>
                                <input type="number" class="form-control editable-fields" name="overall_score"
                                    {{ isset($is_view) ? 'disabled' : '' }}
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
                            <textarea class="form-control editable-fields" name="comments" {{ isset($is_view) ? 'disabled' : '' }}>{{ old('comments') ? old('comments') : $permit_application->testResults?->comments }}</textarea>
                        </div>
                        <div class="mt-3" style="{{ isset($is_view) ? 'display:none' : '' }}" id="reason_div">
                            <label for="" class="form-label">
                                <span class="fw-bold text-danger">
                                    *
                                </span>
                                Reason for edit
                            </label>
                            <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="">
                            <button class="btn btn-warning mt-3" type="button" onclick="makeEditable()"
                                style="{{ !isset($is_view) ? 'display:none' : '' }}" id="editBtn">
                                Edit Results
                            </button>
                            <button class="btn btn-primary mt-3" type="button" onclick="showLoading(this)"
                                style="{{ isset($is_view) ? 'display:none' : '' }}" id="updateBtn">
                                Update Results
                            </button>
                            <a class="btn btn-danger mt-3"
                                href="{{ strpos(URL::previous(), 'advance-search/show') != false ? '/advance-search/create' : URL::previous() }}">
                                Cancel
                            </a>
                        </div>
                    </form>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="text-muted">
                                Transactions
                            </h4>
                        </div>
                        <div class="card-body">
                            @include('partials.tables.edit_transactions_table')
                        </div>
                    </div>
                </div>
            </div>
            <script>
                window.onload = () => {
                    if (document.querySelectorAll('p.text-danger')[0]) {
                        makeEditable();
                    }
                }

                function makeEditable() {
                    document.querySelectorAll('.editable-fields').forEach((element) => {
                        element.removeAttribute('disabled');
                    });
                    document.getElementById('updateBtn').style.display = "";
                    document.getElementById('editBtn').style.display = "none";
                    document.getElementById('reason_div').style.display = "";
                }
            </script>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
