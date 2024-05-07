@extends('partials.layouts.layout')

@section('title', 'Dashboard')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('food_handlers_permit.renew') }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <h3>
                            Renew Food Handler's Application
                        </h3>
                        <hr>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger">*</span>
                                Permit Type
                                <span class="text-danger"></span>
                            </label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="permit_type" value="regular"
                                    {{ old('permit_type') ? (old('permit_type') == 'regular' ? 'checked' : '') : ($application->permit_type == 'regular' ? 'checked' : '') }}>
                                <label for="" class="form-check-label">Regular</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="permit_type" value="student"
                                    {{ old('permit_type') ? (old('permit_type') == 'student' ? 'checked' : '') : ($application->permit_type == 'student' ? 'checked' : '') }}>
                                <label for="" class="form-check-label">Student</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="permit_type" value="teacher"
                                    {{ old('permit_type') ? (old('permit_type') == 'teacher' ? 'checked' : '') : ($application->permit_type == 'teacher' ? 'checked' : '') }}>
                                <label for="" class="form-check-label">Teacher</label>
                            </div>
                            @error('permit_type')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3" id="no_of_years" style="display:none">
                            <span class="text-danger">*</span>
                            <label for="" class="form-label">Number of Years</label>
                            <input type="text" class="form-control" name="no_of_years"
                                value="{{ old('no_of_years') ? old('no_of_years') : $application->no_of_years }}" />
                            @error('no_of_years')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger">*</span>
                                Permit Category
                            </label>
                            <select id="" class="form-control" name="permit_category_id">
                                <option readonly disabled selected>Please select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('permit_category_id') ? (old('permit_category_id') == $category->id ? 'selected' : '') : ($application->permit_category_id == $category->id ? 'selected' : '') }}>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('permit_category_id')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger">*</span>
                                    First Name
                                </label>
                                <input type="text" class="form-control" name="firstname"
                                    value="{{ old('firstname') ? old('firstname') : $application->firstname }}"
                                    oninput="this.value = this.value.toUpperCase()">

                                @error('firstname')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    Middle Name
                                </label>
                                <input type="text" class="form-control" name="middlename"
                                    value="{{ old('middlename') ? old('middlename') : $application->middlename }}"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger">*</span>
                                    Last Name
                                </label>
                                <input type="text" class="form-control" name="lastname"
                                    value="{{ old('lastname') ? old('lastname') : $application->lastname }}"
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('lastname')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger">*</span>
                                Address
                            </label>
                            <input type="text" class="form-control" name="address"
                                value="{{ old('address') ? old('address') : $application->address }}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <img src="{{ asset('storage/' . $application->photo_upload) }}" alt=""
                                style="height:30vh;width:40%">
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Upload Photo</label>
                            <input type="file" class="form-control" name="photo_upload">
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger">*</span>
                                    Gender
                                </label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="gender" value="Male"
                                        {{ old('gender') ? (old('gender') == 'Male' ? 'checked' : '') : (strtoupper($application->gender) == 'MALE' ? 'checked' : '') }}>
                                    <label for="" class="form-check-label">Male</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="gender" value="Female"
                                        {{ old('gender') ? (old('gender') == 'Female' ? 'checked' : '') : (strtoupper($application->gender) == 'FEMALE' ? 'checked' : '') }}>
                                    <label for="" class="form-check-label">Female</label>
                                </div>
                                @error('gender')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger">*</span>
                                    Date of Birth
                                </label>
                                <input type="date" class="form-control" name="date_of_birth"
                                    value="{{ old('date_of_birth') ? old('date_of_birth') : $application->date_of_birth }}">
                                @error('date_of_birth')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Cell Phone</label>
                                <input type="text" class="form-control" name="cell_phone"
                                    value="{{ old('cell_phone') ? old('cell_phone') : $application->cell_phone }}"
                                    id="cell_phone">
                                @error('cell_phone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Home Phone</label>
                                <input type="text" class="form-control" name="home_phone"
                                    value="{{ old('home_phone') ? old('home_phone') : $application->home_phone }}"
                                    id="home_phone">
                                @error('home_phone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Work Phone</label>
                                <input type="text" class="form-control" name="work_phone"
                                    value="{{ old('work_phone') ? old('work_phone') : $application->work_phone }}"
                                    id="work_phone">
                                @error('work_phone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Tax Registration Number</label>
                                <input type="text" class="form-control" name="trn"
                                    value="{{ old('trn') ? old('trn') : $application->trn }}" id="trn">
                                @error('trn')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Email</label>
                                <input type="text" class="form-control" name="email"
                                    value="{{ old('email') ? old('email') : $application->email }}"
                                    oninput="this.value = this.value.toUpperCase()">
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Occupation</label>
                                <input type="text" class="form-control" name="occupation"
                                    value="{{ old('occupation') ? old('occupation') : $application->occupation }}"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Name of Employer</label>
                                <input type="text" class="form-control" name="employer"
                                    value="{{ old('employer') ? old('employer') : $application->employer }}"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Business Address of Employer</label>
                                <input type="text" class="form-control" name="employer_address"
                                    value="{{ old('employer_address') ? old('employer_address') : $application->employer_address }}"
                                    oninput="this.value = this.value.toUpperCase()">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <span class="text-danger">*</span>
                                <label for="" class="form-label">Ever applied for a permit before?</label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="applied_before" value="1"
                                        {{ old('applied_before') == '1' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="applied_before" value="0"
                                        {{ old('applied_before') == '0' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">No</label>
                                </div>
                                @error('applied_before')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col" id="granted" style="display:none">
                                <span class="text-danger">*</span>
                                <label for="" class="form-label">Was the application granted or refused?</label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" value="1" name="granted"
                                        {{ old('granted') == '1' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">Granted</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" value="0" name="granted"
                                        {{ old('granted') == '0' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">Refused</label>
                                </div>
                                @error('granted')
                                    <p class="text-danger">The granted field is required when person has applied before</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="" class="form-label">If refused, state reason(20 chars min, 100
                                max)</label>
                            <textarea name="reason" id="" class="form-control" oninput="this.value = this.value.toUpperCase()">{{ old('reason') }}</textarea>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <span class="text-danger">*</span>
                                <label for="" class="form-label">Schedule Appointment</label>
                                <select id="" class="form-control" name="exam_session">
                                    <option disabled selected>Please select an exam session</option>
                                    @foreach ($appointments_available as $appointment_avaiable)
                                        <option value="{{ $appointment_avaiable->id }}"
                                            {{ old('exam_session') == $appointment_avaiable->id ? 'selected' : '' }}>
                                            {{ $appointment_avaiable->permitCategory?->name }}
                                            - {{ $appointment_avaiable->exam_day }}
                                            {{ $appointment_avaiable->exam_start_time }}
                                            -{{ $appointment_avaiable->examSites?->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('exam_session')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <span class="text-danger">*</span>
                                <label for="" class="form-label">Exam Date</label>
                                <input type="date" class="form-control" name="exam_date"
                                    value="{{ old('exam_date') }}">
                                @error('exam_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-danger">*</span>
                            <label for="" class="form-label">Application Date</label>
                            <input type="date" class="form-control" name="application_date"
                                value="{{ old('application_date') }}">
                            @error('application_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <input type="text" class="form-control mt-3" value="{{ $application->id }}"
                                name="old_application_id" hidden>
                        </div>
                        <div class="mt-4">
                            <button class="btn btn-primary" type="button" onclick="showLoading(this)">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://unpkg.com/imask"></script>
        <script>
            const trn = document.getElementById('trn');
            const cell_phone = document.getElementById('cell_phone');
            const work_phone = document.getElementById('work_phone');
            const home_phone = document.getElementById('home_phone');

            const maskOptions = {
                mask: '000-000-000'
            }

            const maskOptions2 = {
                mask: '1(000)000-0000'
            }

            const mask1 = IMask(trn, maskOptions);
            const mask2 = IMask(cell_phone, maskOptions2);
            const mask3 = IMask(work_phone, maskOptions2);
            const mask4 = IMask(home_phone, maskOptions2);

            const hamBurger = document.querySelector(".toggle-btn");

            hamBurger.addEventListener("click", function() {
                document.querySelector("#sidebar").classList.toggle("expand");
            });
        </script>
        <script>
            $(document).ready(function() {
                $("input[name='permit_type']").change(function() {
                    if ($("input[name='permit_type']:checked").val() == "student") {
                        document.getElementById("no_of_years").style.display = "";
                    } else {
                        document.getElementById("no_of_years").style.display = "none";
                    }
                })

                $("input[name='applied_before']").change(function() {
                    if ($("input[name='applied_before']:checked").val() == "1") {
                        document.getElementById('granted').style.display = "";
                    } else {
                        document.getElementById('granted').style.display = "none";
                    }
                })
            })

            window.onload = () => {
                if ($("input[name='applied_before']:checked").val() == "1") {
                    document.getElementById('granted').style.display = "";
                }
                if ($("input[name='permit_type']:checked").val() == "student") {
                    document.getElementById("no_of_years").style.display = "";
                }
            }
        </script>
        @include('partials.messages.loading_message')
    </div>
@endsection
