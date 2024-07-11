@extends('partials.layouts.layout')

@section('title', 'View Health Interview')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid">
            {{-- <div class="card"> --}}
            {{-- <div class="card-header"> --}}
            <h2 class="text-muted">View Health Interview for {{ $application->firstname . ' ' . $application->lastname }}
            </h2>
            {{-- </div> --}}
            {{-- <div class="card-body"> --}}
            <div class="card">
                <div class="card-header">
                    <h4 class="text-muted">
                        Applicant Information
                    </h4>
                </div>
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
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Home Address</label>
                            <input type="text" class="form-control" disabled value="{{ $application->address }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Employer Address</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $application->establishment_clinic_id == '' ? $application->employer_address : $application->establishmentClinics?->name }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Date of Birth</label>
                        <input type="text" class="form-control" disabled value="{{ $application->date_of_birth }}">
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Cell Phone</label>
                            <input type="text" class="form-control" value="{{ $application->cell_phone }}" disabled>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Home Phone</label>
                            <input type="text" class="form-control" value="{{ $application->home_phone }}" disabled>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Work Phone</label>
                            <input type="text" class="form-control" value="{{ $application->work_phone }}" disabled>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <form action="{{ route('health-interview.update', ['id' => $application->healthInterviews?->id]) }}"
                    method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-header">
                        <h4 class="text-muted">
                            Health Interview Information
                        </h4>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="fw-bold text-danger">*</span>
                                    Literacy
                                </label>
                                <select name="literate" id="" class="form-select" disabled>
                                    <option value="1"
                                        {{ old('literate') != null ? (old('literate') == '1' ? 'selected' : '') : ($application->healthInterviews?->literate == '1' ? 'selected' : '') }}>
                                        YES</option>
                                    <option value="0"
                                        {{ old('literate') != null ? (old('literate') == '0' ? 'selected' : '') : ($application->healthInterviews?->literate == '0' ? 'selected' : '') }}>
                                        NO</option>
                                </select>
                                @error('literate')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="fw-bold text-danger">*</span>
                                    Has you ever had typhoid or paratyphoid?
                                </label>
                                <select name="typhoid" id="" class="form-select" disabled>
                                    <option value="1"
                                        {{ old('typhoid') != null ? (old('typhoid') == '1' ? 'selected' : '') : ($application->healthInterviews?->typhoid == '1' ? 'selected' : '') }}>
                                        YES</option>
                                    <option value="0"
                                        {{ old('typhoid') != null ? (old('typhoid') == '0' ? 'selected' : '') : ($application->healthInterviews?->typhoid == '0' ? 'selected' : '') }}>
                                        NO</option>
                                </select>
                                @error('typhoid')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="fw-bold text-danger">*</span>
                                    Have you ever lived abroad?
                                </label>
                                <select name="lived_abroad" id="" class="form-select" disabled>
                                    <option value="1"
                                        {{ old('lived_abroad') != null ? (old('lived_abroad') == '1' ? 'selected' : '') : ($application->healthInterviews?->lived_abroad == '1' ? 'selected' : '') }}>
                                        YES</option>
                                    <option value="0"
                                        {{ old('lived_abroad') != null ? (old('lived_abroad') == '0' ? 'selected' : '') : ($application->healthInterviews?->lived_abroad == '0' ? 'selected' : '') }}>
                                        NO</option>
                                </select>
                                @error('lived_abroad')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="fw-bold text-danger">*</span>
                                    If yes, where?
                                </label>
                                <input type="text" class="form-control" name="lived_abroad_location" disabled
                                    value="{{ old('lived_abroad_location') ? old('lived_abroad_location') : $application->healthInterviews?->lived_abroad_location }}">
                                @error('lived_abroad_location')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="fw-bold text-danger">*</span>
                                    If yes, when?
                                </label>
                                <input type="text" class="form-control" disabled name="lived_abroad_date"
                                    value="{{ old('lived_abroad_date') ? old('lived_abroad_date') : $application->healthInterviews?->lived_abroad_date }}">
                                @error('lived_abroad_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col col-md-4">
                                <label for="" class="form-label">
                                    <span class="fw-bold text-danger">*</span>
                                    Have you travelled abroad recently?
                                </label>
                                <select name="travel_abroad" id="" class="form-select" disabled>
                                    <option value="1"
                                        {{ old('travel_abroad') != null ? (old('travel_abroad') == '1' ? 'selected' : '') : ($application->healthInterviews?->travel_abroad == '1' ? 'selected' : '') }}>
                                        YES</option>
                                    <option value="0"
                                        {{ old('travel_abroad') != null ? (old('travel_abroad') == '0' ? 'selected' : '') : ($application->healthInterviews?->travel_abroad == '0' ? 'selected' : '') }}>
                                        NO</option>
                                </select>
                                @error('travel_abroad')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <h5 class="text-muted mt-4">
                            Physical Examinations (Oberservations)
                        </h5>
                        <div class="row mt-1">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Whitlow
                                </label>
                                <select name="" id="" class="form-select" disabled>
                                    <option selected disabled>Select an option</option>
                                    <option value="absent"
                                        {{ old('whitlow') ? (old('whitlow') == 'absent' ? 'selected' : '') : ($application->healthInterviews?->whitlow == 'absent' ? 'selected' : '') }}>
                                        Absent
                                    </option>
                                    <option value="present"
                                        {{ old('whitlow') ? (old('whitlow') == 'present' ? 'selected' : '') : ($application->healthInterviews?->whitlow == 'present' ? 'selected' : '') }}>
                                        Present
                                    </option>
                                    <option value="undetermined"
                                        {{ old('whitlow') ? (old('whitlow') == 'undetermined' ? 'selected' : '') : ($application->healthInterviews?->whitlow == 'undetermined' ? 'selected' : '') }}>
                                        Undetermined</option>
                                </select>
                                @error('whitlow')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Hands
                                </label>
                                <select name="" id="" class="form-select" disabled>
                                    <option selected disabled>Select an option</option>
                                    <option value="satisfactory"
                                        {{ old('hands_condition') ? (old('hands_condition') == 'satisfactory' ? 'selected' : '') : ($application->healthInterviews?->hands_condition == 'satisfactory' ? 'selected' : '') }}>
                                        Satisfactory
                                    </option>
                                    <option value="unsatisfactory"
                                        {{ old('hands_condition') ? (old('hands_condition') == 'unsatisfactory' ? 'selected' : '') : ($application->healthInterviews?->hands_condition == 'unsatisfactory' ? 'selected' : '') }}>
                                        Unsatisfactory
                                    </option>
                                    <option value="undetermined"
                                        {{ old('hands_condition') ? (old('hands_condition') == 'undetermined' ? 'selected' : '') : ($application->healthInterviews?->hands_condition == 'undetermined' ? 'selected' : '') }}>
                                        Undetermined
                                    </option>
                                </select>
                                @error('hands_condition')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Fingernails
                                </label>
                                <select name="" id="" class="form-select" disabled>
                                    <option selected disabled>Select an option</option>
                                    <option value="satisfactory"
                                        {{ old('fingernails_condition') ? (old('fingernails_condition') == 'satisfactory' ? 'selected' : '') : ($application->healthInterviews?->fingernails_condition == 'satisfactory' ? 'selected' : '') }}>
                                        Satisfactory
                                    </option>
                                    <option value="unsatisfactory"
                                        {{ old('fingernails_condition') ? (old('fingernails_condition') == 'unsatisfactory' ? 'selected' : '') : ($application->healthInterviews?->fingernails_condition == 'unsatisfactory' ? 'selected' : '') }}>
                                        Unsatisfactory</option>
                                    <option value="undetermined"
                                        {{ old('fingernails_condition') ? (old('fingernails_condition') == 'undetermined' ? 'selected' : '') : ($application->healthInterviews?->fingernails_condition == 'undetermined' ? 'selected' : '') }}>
                                        Undetermined
                                    </option>
                                </select>
                                @error('fingernails_condition')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Teeth
                                </label>
                                <select name="" id="" class="form-select" disabled>
                                    <option selected disabled>Select an option</option>
                                    <option value="satisfactory"
                                        {{ old('teeth_condition') ? (old('teeth_condition') == 'satisfactory' ? 'selected' : '') : ($application->healthInterviews?->teeth_condition == 'satisfactory' ? 'selected' : '') }}>
                                        Satisfactory
                                    </option>
                                    <option value="unsatisfactory"
                                        {{ old('teeth_condition') ? (old('teeth_condition') == 'unsatisfactory' ? 'selected' : '') : ($application->healthInterviews?->teeth_condition == 'unsatisfactory' ? 'selected' : '') }}>
                                        Unsatisfactory
                                    </option>
                                    <option value="undetermined"
                                        {{ old('teeth_condition') ? (old('teeth_condition') == 'undetermined' ? 'selected' : '') : ($application->healthInterviews?->teeth_condition == 'undetermined' ? 'selected' : '') }}>
                                        Undetermined
                                    </option>
                                </select>
                                @error('teeth_condition')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <h5 class="text-muted mt-4">
                            Medical Examination (If Conducted)
                        </h5>
                        <div class="row mt-1">
                            <div class="col">
                                <label for="" class="form-label">Test Recommended</label>
                                <input type="text" class="form-control" disabled name="tests_recommended"
                                    value="{{ old('tests_recommended') ? old('tests_recommended') : $application->healthInterviews?->tests_recommended }}">
                                @error('tests_recommended')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Results</label>
                                <input type="number" class="form-control" name="tests_results"
                                    value="{{ old('tests_results') ? old('tests_results') : $application->healthInterviews?->tests_results }}"
                                    disabled>
                                @error('tests_results')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <h5 class="text-muted mt-4">
                            Doctor Contact Information
                        </h5>
                        <div class="row mt-1">
                            <div class="col">
                                <label for="" class="form-label">Name</label>
                                <input type="text" class="form-control" name="doctor_name"
                                    value="{{ old('doctor_name') ? old('doctor_name') : $application->healthInterviews?->doctor_name }}"
                                    disabled>
                                @error('doctor_name')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Address</label>
                                <input type="text" class="form-control" name="doctor_address"
                                    value="{{ old('doctor_address') ? old('doctor_address') : $application->healthInterviews?->doctor_address }}"
                                    disabled>
                                @error('doctor_address')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Telephone</label>
                                <input type="text" class="form-control" name="doctor_tele"
                                    value="{{ old('doctor_tele') ? old('doctor_tele') : $application->healthInterviews?->doctor_tele }}"
                                    disabled>
                                @error('doctor_tele')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3" id="reason_div" style="display:none">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">
                                    *
                                </span>
                                Reason for Edit
                            </label>
                            <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="hidden" class="form-control" id="edit_mode"
                            value="{{ isset($edit_mode) ? 1 : '' }}">
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-warning shadow" onclick="makeInterviewEditable()" id="editBtn"
                            type="button">
                            Edit Health Interview
                        </button>
                        <button class="btn btn-primary" style="display:none" id="updBtn" type="submit">
                            <i class="bi bi-pencil-square"></i>
                            Update Applicant Information
                        </button>
                    </div>
                </form>
            </div>
            <div class="row mt-4 mb-4">
                <div class="col col-md-6 col-sm-12">
                    <div class="card" style="height:100%">
                        <div class="card-header">
                            <h4 class="text-muted">Symptoms</h4>
                        </div>
                        <div class="card-body">
                            @include('partials.tables.health_interview_symptoms')
                        </div>
                    </div>
                </div>
                <div class="col col-md-6 col-sm-12">
                    <div class="card" style="height:100%">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h4 class="text-muted">Travel Hitory</h4>
                                </div>
                                <div class="col col-auto">
                                    <button class="btn btn-primary"
                                        onclick="addTravelHistory({{ json_encode($application->firstname . ' ' . $application->lastname) }}, {{ json_encode($application->id) }}, {{ json_encode($application->healthInterviews?->permit_application_id ? 1 : 2) }})">
                                        Add History
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('partials.tables.travel_history')
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4 class="text-muted">
                        Transactions
                    </h4>
                </div>
                <div class="card-body">
                    @include('partials.tables.edit_transactions_table')
                </div>
            </div>
            {{-- </div> --}}
            {{-- </div> --}}
        </div>
        <script>
            function addTravelHistory(applicant_name, applicant_id, application_type) {
                swal.fire({
                    title: "Add Travel History to " + applicant_name,
                    text: 'Enter destination of travel',
                    input: 'text',
                    icon: 'question',
                    inputAttributes: {
                        required: true
                    },
                    showCancelButton: true,
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        swal.fire({
                            title: "Enter Travel Date of Travel Histroy",
                            text: 'This is required',
                            icon: 'question',
                            input: 'date',
                            inputAttributes: {
                                required: true
                            },
                            showCancelButton: true,
                            showCancelButton: false
                        }).then((result2) => {
                            if (result2.isConfirmed) {
                                swal.fire({
                                    title: "Enter reason for editing travel history.",
                                    input: 'textarea',
                                    icon: 'question',
                                    inputAttributes: {
                                        required: true
                                    },
                                    showCancelButton: true,
                                    showCancelButton: true
                                }).then((result4) => {
                                    if (result4.isConfirmed) {
                                        swal.fire({
                                            title: 'Are you sure you want to add Travel History',
                                            icon: 'warning',
                                            showCancelButton: true,
                                            showConfirmButton: true
                                        }).then((result3) => {
                                            if (result3.isConfirmed) {
                                                $.post({!! json_encode(url('/health-interviews/travel-history/create/')) !!} + '/' +
                                                    applicant_id, {
                                                        _method: "POST",
                                                        data: {
                                                            destination: result.value,
                                                            travel_date: result2.value,
                                                            edit_reason: result4.value,
                                                            application_type: application_type
                                                        },
                                                        _token: "{{ csrf_token() }}"
                                                    }).then((data) => {
                                                    if (data == 'success') {
                                                        swal.fire({
                                                            icon: 'success',
                                                            title: "Travel History has been added successfully"
                                                        }).then(esc => {
                                                            if (esc) {
                                                                location
                                                                    .reload();
                                                            }
                                                        })
                                                    } else {
                                                        swal.fire({
                                                            title: 'Error adding Travel History',
                                                            icon: 'error',
                                                            text: data
                                                        })
                                                    }
                                                })
                                            }
                                        })
                                    }
                                })
                            }
                        })
                    }
                })
            }
            window.onload = () => {
                if (document.getElementById('edit_mode').value == '1' || document.querySelectorAll('p.text-danger')[0]) {
                    makeInterviewEditable();
                }
            }

            function makeInterviewEditable() {
                document.querySelector('select[name=literate]').removeAttribute('disabled');
                document.querySelector('select[name=typhoid]').removeAttribute('disabled');
                document.querySelector('select[name=lived_abroad]').removeAttribute('disabled');
                document.querySelector('input[name=lived_abroad_location]').removeAttribute('disabled');
                document.querySelector('input[name=lived_abroad_date]').removeAttribute('disabled');
                document.querySelector('select[name=travel_abroad]').removeAttribute('disabled');
                document.querySelector('input[name=tests_recommended]').removeAttribute('disabled');
                document.querySelector('input[name=tests_results]').removeAttribute('disabled');
                document.querySelector('input[name=doctor_name]').removeAttribute('disabled');
                document.querySelector('input[name=doctor_address]').removeAttribute('disabled');
                document.querySelector('input[name=doctor_tele]').removeAttribute('disabled');
                document.getElementById('editBtn').style.display = "none";
                document.getElementById('updBtn').style.display = "";
                document.getElementById('reason_div').style.display = "";
            }
        </script>
    </div>
@endsection
