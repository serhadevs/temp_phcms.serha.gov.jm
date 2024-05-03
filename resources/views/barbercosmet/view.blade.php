@extends('partials.layouts.layout')

@section('title', 'View Barber/Cosmet Application')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="row">
                <div class="col">
                    <h2 class="text-muted">
                        Barber/Cosmet. Application {{ $application->firstname . ' ' . $application->lastname }}
                    </h2>
                </div>
                <div class="col-auto">
                    <button class="btn btn-danger" onclick="history.back()">
                        <i class="bi bi-box-arrow-left"></i>
                        Go Back
                    </button>
                </div>
            </div>
            <div class="card mt-3">
                <input type="hidden" value="{{ isset($edit_mode) ? '1' : '' }}" id="edit_mode">
                <div class="card-body">
                    <h4 class="text-muted">
                        Applicant Infomation
                    </h4>
                    <hr>
                    <form action="{{ route('barber-cosmet.edit.applicant') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $application->id }}">
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    First Name
                                </label>
                                <input type="text" class="form-control applicant_inputs" name="firstname"
                                    value="{{ old('firstname') ? old('firstname') : $application?->firstname }}"
                                    oninput="this.value=this.value.toUpperCase()" disabled>
                                @error('firstname')
                                    <p class="text-danger applicant-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Middle Name</label>
                                <input type="text" class="form-control applicant_inputs" name="middlename"
                                    value="{{ old('middlename') ? old('middlename') : $application?->middlename }}"
                                    oninput="this.value=this.value.toUpperCase()" disabled>
                                @error('middlename')
                                    <p class="text-danger applicant-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Last Name
                                </label>
                                <input type="text" class="form-control applicant_inputs" name="lastname"
                                    value="{{ old('lastname') ? old('lastname') : $application?->lastname }}"
                                    oninput="this.value=this.value.toUpperCase()" disabled>
                                @error('lastname')
                                    <p class="text-danger applicant-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Address
                            </label>
                            <input type="text" class="form-control applicant_inputs" name="address"
                                value="{{ old('address') ? old('address') : $application?->address }}"
                                oninput="this.value=this.value.toUpperCase()" disabled>
                            @error('address')
                                <p class="text-danger applicant-error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Birth Date
                                </label>
                                <input type="date" class="form-control applicant_inputs" name="date_of_birth"
                                    value="{{ old('date_of_birth') ? old('date_of_birth') : $application?->date_of_birth }}"
                                    disabled>
                                @error('date_of_birth')
                                    <p class="text-danger applicant-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Gender
                                </label>
                                <select name="sex" id="" class="form-select applicant_inputs" disabled>
                                    <option value="male"
                                        {{ old('sex') ? (old('sex') == 'male' ? 'selected' : '') : ($application->sex == 'male' ? 'selected' : '') }}>
                                        Male</option>
                                    <option value="female"
                                        {{ old('sex') ? (old('sex') == 'female' ? 'selected' : '') : ($application->sex == 'female' ? 'selected' : '') }}>
                                        Female
                                    </option>
                                </select>
                                @error('sex')
                                    <p class="text-danger applicant-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 row">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Telephone
                                </label>
                                <input type="text" class="form-control applicant_inputs" name="telephone"
                                    value="{{ old('telephone') ? old('telephone') : $application?->telephone }}" disabled>
                                @error('telephone')
                                    <p class="text-danger applicant-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Email</label>
                                <input type="email" class="form-control applicant_inputs" name="email"
                                    value="{{ old('email') ? old('email') : $application?->email }}"
                                    oninput="this.value=this.value.toUpperCase()" disabled>
                                @error('email')
                                    <p class="text-danger applicant-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Tax Registration Number (TRN)
                                </label>
                                <input type="text" class="form-control" name="trn"
                                    value="{{ old('trn') ? old('trn') : $application?->trn }}" disabled>
                                @error('trn')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div id="update_1" style="display:none">
                            <button class="btn btn-primary mt-3" type="submit">
                                <i class="bi bi-pencil-square"></i>
                                Update Applicant Information
                            </button>
                            <button class="btn btn-danger mt-3" onclick="disableApplicantInfo()" type="button">
                                <i class="bi bi-box-arrow-left"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-warning mt-3" onclick="enableEditing(1)"
                        id="edit_button_1">Edit Section</button>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-body">
                    <h4 class="text-muted" id="employment">
                        Employment & Application Info
                    </h4>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">Application ID</label>
                            <label for="" class="form-control"
                                style="background:#e9ecef">{{ $application->id }}</label>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Permit Number</label>
                            <label for="" class="form-control"
                                style="background:#e9ecef">{{ $application->permit_no }}</label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Sign Off Status</label>
                            <input class="form-control" disabled
                                value="{{ $application->sign_off_status == '1' ? 'SIGNED OFF' : 'NO SIGN OFF' }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Payment Status</label>
                            <input class="form-control" value="{{ empty($application->payment) ? 'NOT PAID' : 'PAID' }}"
                                disabled>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Added By</label>
                            <input value="{{ $application?->user?->firstname . ' ' . $application?->user?->lastname }}"
                                class="form-control" disabled>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Application Date</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $application->application_date }}">
                        </div>
                    </div>
                    <form action="{{ route('barber-cosmet.edit.employment') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $application->id }}">
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Occupation</label>
                                <input type="text" class="form-control employ_inputs" name="occupation"
                                    value="{{ old('occupation') ? old('occupation') : $application?->occupation }}"
                                    oninput="this.value=this.value.toUpperCase()" disabled>
                                @error('occupation')
                                    <p class="text-danger emp_error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Name of Employer</label>
                                <input type="text" class="form-control employ_inputs" name="employer"
                                    value="{{ old('employer') ? old('employer') : $application?->employer }}"
                                    oninput="this.value=this.value.toUpperCase()" disabled>
                                @error('employer')
                                    <p class="text-danger emp_error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Business Address of Employer</label>
                                <input type="text" class="form-control employ_inputs" name="employer_address"
                                    value="{{ old('employer_address') ? old('employer_address') : $application?->employer_address }}"
                                    oninput="this.value=this.value.toUpperCase()" disabled>
                                @error('employer_address')
                                    <p class="text-danger emp_error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-3 row">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Have you ever applied for HealthCertification?
                                </label>
                                <select name="applied_before" id="" class="form-select employ_inputs" disabled>
                                    <option value="1"
                                        {{ old('applied_before') ? (old('applied_before') == '1' ? 'selected' : '') : ($application->applied_before == '1' ? 'selected' : '') }}>
                                        Yes</option>
                                    <option value="0"
                                        {{ old('applied_before') ? (old('applied_before') == '0' ? 'selected' : '') : ($application->applied_before == '0' ? 'selected' : '') }}>
                                        No</option>
                                </select>
                                @error('applied_before')
                                    <p class="text-danger emp_error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Was the application granted or refused?
                                </label>
                                <select name="granted" id="" class="form-select employ_inputs" disabled>
                                    <option disabled {{ $application->granted == '' ? 'selected' : '' }}>N/A</option>
                                    <option value="1"
                                        {{ old('granted') == '1' ? 'selected' : ($application->granted == '1' ? 'selected' : '') }}>
                                        Granted</option>
                                    <option value="0"
                                        {{ old('granted') == '0' ? 'selected' : ($application->granted == '0' ? 'selected' : '') }}>
                                        Refused</option>
                                </select>
                                @error('granted')
                                    <p class="text-danger emp_error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">If Reused, state reason(20 chars min, 100
                                max)</label>
                            <textarea name="reason" class="form-control employ_inputs" oninput="this.value=this.value.toUpperCase()" disabled>{{ old('reason') ? old('reason') : $application?->reason }}</textarea>
                            @error('reason')
                                <p class="text-danger emp_error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div id="update_2" style="display:none">
                            <button class="btn btn-primary mt-3" type="submit">
                                <i class="bi bi-pencil-square"></i>
                                Update Application Information
                            </button>
                            <button class="btn btn-danger mt-3" onclick="disableEmployInfo()" type="button">
                                <i class="bi bi-box-arrow-left"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-warning mt-3" onclick="enableEditing(2)"
                        id="edit_button_2">Edit Section</button>
                </div>
            </div>
            <div class="card mt-4 mb-4">
                <div class="card-body">
                    <h4 class="text-muted" id="appointment_info">Appointment Info</h4>
                    <hr>
                    <form action="{{ route('barber-cosmet.edit.appointments') }}" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $application->id }}">
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">Exam Date</label>
                                <input type="date" class="form-control" id="exam_date" name="appointment_date"
                                    value="{{ old('appointment_date') ? old('appointment_date') : $application?->appointment?->first()?->appointment_date }}"
                                    disabled>
                                @error('appointment_date')
                                    <p class="text-danger appointment-error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col" id="exam_time">
                                <label for="" class="form-label">Exam Time</label>
                                <input type="text" class="form-control"
                                    value="{{ strtoupper($application?->appointment?->first()?->examDate?->exam_day . ' - ' . $application?->appointment?->first()?->examDate?->exam_start_time) }}"
                                    disabled>
                            </div>
                            <div class="col" style="display:none" id="book_session">
                                <label for="" class="form-label">Appointment Session</label>
                                <select name="exam_date_id" id="" class="form-select">
                                    @foreach ($exam_sessions as $session)
                                        <option value="{{ $session->id }}"
                                            {{ (old('exam_date_id') ? (old('exam_date_id') == $session->id ? 'selected' : '') : $application?->appointment?->first()?->exam_date_id == $session->id) ? 'selected' : '' }}>
                                            {{ strtoupper($session?->exam_day . ' - ' . $session?->exam_start_time . ' - ' . $session?->examSites?->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('exam_date_id')
                                    <p class="text-danger appointment-error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3" id="exam_site">
                            <label for="" class="form-label">Exam Site</label>
                            <input type="text" class="form-control"
                                value="{{ strtoupper($application?->appointment?->first()?->examDate?->examSites?->name) }}"
                                disabled>
                        </div>
                        <div id="update_3" style="display:none">
                            <button class="btn btn-primary mt-3" type="submit">
                                <i class="bi bi-pencil-square"></i>
                                Update Appointment Information
                            </button>
                            <button class="btn btn-danger mt-3" onclick="disableAppointmentInfo()" type="button">
                                <i class="bi bi-box-arrow-left"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                    <button type="button" class="btn btn-warning mt-3" onclick="enableEditing(3)"
                        id="edit_button_3">Edit Section</button>
                </div>
            </div>
        </div>
        <script>
            window.onload = () => {
                if (document.getElementById('edit_mode').value == '1') {
                    enableEditing(1);
                }
                applicant_error = document.querySelectorAll('.applicant-error');
                emp_error = document.querySelectorAll('.emp_error');
                appointment_error = document.querySelectorAll('.appointment-error');
                if (applicant_error[0]) {
                    enableEditing(1);
                }

                if (emp_error[0]) {
                    document.getElementById('employment').scrollIntoView({
                        behaviour: "smooth"
                    });
                    enableEditing(2);
                }

                if (appointment_error[0]) {
                    document.getElementById('appointment_info').scrollIntoView({
                        behaviour: "smooth"
                    });
                    enableEditing(3);
                }
            }

            function enableEditing(section_id) {
                if (section_id == "1") {
                    disableEmployInfo();
                    disableAppointmentInfo();
                    applicant_inputs = document.querySelectorAll('.applicant_inputs');
                    applicant_inputs.forEach((element) => {
                        element.removeAttribute('disabled');
                    })
                    document.getElementById('update_1').style.display = "";
                    document.getElementById('edit_button_1').style.display = "none";
                } else if (section_id == 2) {
                    disableApplicantInfo();
                    disableAppointmentInfo();
                    employ_info = document.querySelectorAll('.employ_inputs');
                    employ_info.forEach((element) => {
                        element.removeAttribute('disabled');
                    })
                    document.getElementById('update_2').style.display = "";
                    document.getElementById('edit_button_2').style.display = "none";
                } else if (section_id == 3) {
                    disableApplicantInfo();
                    disableEmployInfo();
                    document.getElementById('book_session').style.display = "";
                    document.getElementById('exam_time').style.display = "none";
                    document.getElementById('exam_site').style.display = "none";
                    document.getElementById('exam_date').removeAttribute('disabled');

                    document.getElementById('update_3').style.display = "";
                    document.getElementById('edit_button_3').style.display = "none";
                }
            }

            function disableApplicantInfo() {
                applicant_inputs = document.querySelectorAll('.applicant_inputs');
                applicant_inputs.forEach((element) => {
                    element.setAttribute('disabled', true);
                })
                document.getElementById('update_1').style.display = "none";
                document.getElementById('edit_button_1').style.display = "";
            }

            function disableEmployInfo() {
                employ_info = document.querySelectorAll('.employ_inputs');
                employ_info.forEach((element) => {
                    element.setAttribute('disabled', true);
                })
                document.getElementById('update_2').style.display = "none";
                document.getElementById('edit_button_2').style.display = "";
            }

            function disableAppointmentInfo() {
                document.getElementById('book_session').style.display = "none";
                document.getElementById('exam_time').style.display = "";
                document.getElementById('exam_site').style.display = "";
                document.getElementById('exam_date').setAttribute('disabled', true);

                document.getElementById('update_3').style.display = "none";
                document.getElementById('edit_button_3').style.display = "";
            }
        </script>
    </div>
@endsection
