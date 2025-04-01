@extends('partials.layouts.layout')

@section('title', 'Food Handlers Permit')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            {{-- @include('partials.messages.messages') --}}
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p class="text-success"><strong>{{ $message }}</strong></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p class="text-danger font-weight-bold">{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (isset($clinic_permit_data))
                <h4 class="text-muted">
                    Application
                    {{ $clinic_permit_data['completed_permits_total'] + 1 }}
                    of
                    {{ $clinic_permit_data['no_of_employees'] }}
                </h4>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="text-muted">
                        Create Food Handler Application
                    </h3>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('food_handlers_permit.store') }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf

                        @if (isset($clinic_permit))
                            <input type="text" class="form-control" name="establishment_clinic_id"
                                value="{{ $clinic_permit_data['clinic_app_id'] }}" hidden>
                            <input type="text" class="form-control" name="exam_date"
                                value="{{ $clinic_permit->proposed_date }}" hidden>
                        @endif
                        <div class="">
                            <label for="" class="form-label">
                                <span class="text-danger">*</span>
                                Permit Type
                                <span class="text-danger"></span>
                            </label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="permit_type" value="regular"
                                    {{ old('permit_type') == 'regular' ? 'checked' : '' }}>
                                <label for="" class="form-check-label">Regular</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="permit_type" value="student"
                                    {{ old('permit_type') == 'student' ? 'checked' : '' }}>
                                <label for="" class="form-check-label">Student</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="permit_type" value="teacher"
                                    {{ old('permit_type') == 'teacher' ? 'checked' : '' }}>
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
                                value="{{ old('no_of_years') }}" />
                            @error('no_of_years')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger">*</span>
                                Permit Category
                            </label>
                            <select id="" class="form-select" name="permit_category_id"
                                onchange="populateSchedule(this.value)">
                                <option readonly disabled selected>Please select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('permit_category_id') == $category->id ? 'selected' : '' }}>
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
                                <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}"
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
                                    value="{{ old('middlename') }}" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger">*</span>
                                    Last Name
                                </label>
                                <input type="text" class="form-control" name="lastname"
                                    value="{{ old('lastname') }}" oninput="this.value = this.value.toUpperCase()">
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
                            <input type="text" class="form-control" name="address" value="{{ old('address') }}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
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
                                        {{ old('gender') == 'Male' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">Male</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="gender" value="Female"
                                        {{ old('gender') == 'Female' ? 'checked' : '' }}>
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
                                    value="{{ old('date_of_birth') }}" max = "{{ date('Y-m-d') }}">
                                @error('date_of_birth')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Cell Phone</label>
                                <input type="text" class="form-control" name="cell_phone"
                                    value="{{ old('cell_phone') }}" id="cell_phone">
                                @error('cell_phone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Home Phone</label>
                                <input type="text" class="form-control" name="home_phone"
                                    value="{{ old('home_phone') }}" id="home_phone">
                                @error('home_phone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Work Phone</label>
                                <input type="text" class="form-control" name="work_phone"
                                    value="{{ old('work_phone') }}" id="work_phone">
                                @error('work_phone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Tax Registration Number</label>
                                <input type="text" class="form-control" name="trn" value="{{ old('trn') }}"
                                    id="trn">
                                @error('trn')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
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
                                    value="{{ old('occupation') }}" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Name of Employer</label>
                                <input type="text" class="form-control" name="employer"
                                    value="{{ old('employer') }}" oninput="this.value = this.value.toUpperCase()">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Business Address of Employer</label>
                                <input type="text" class="form-control" name="employer_address"
                                    value="{{ old('employer_address') }}"
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
                            <div class="col">
                                <label for="" class="form-label">If refused, state reason(20 chars min, 100
                                    max)</label>
                                <textarea name="reason" id="" class="form-control" oninput="this.value = this.value.toUpperCase()">{{ old('reason') }}</textarea>
                            </div>

                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <span class="text-danger">*</span>
                                <label for="" class="form-label">Schedule Appointment</label>
                                <select id="exam_session" class="form-select" name="exam_session" id="exam_session">
                                    @if (isset($clinic_permit))
                                        <option value="{{ $clinic_permit->id }}">
                                            {{ $clinic_permit->address }} - {{ $clinic_permit->proposed_time }}
                                        </option>
                                    @else
                                        {{-- <option disabled selected>Please select an exam session</option> --}}
                                        {{-- @foreach ($appointments_available as $appointment_avaiable)
                                            <option value="{{ $appointment_avaiable->id }}"
                                                {{ old('exam_session') == $appointment_avaiable->id ? 'selected' : '' }}>

                                                {{ $appointment_avaiable->permitCategory?->name }}
                                                - {{ strtoupper($appointment_avaiable->exam_day) }}
                                                {{ $appointment_avaiable->exam_start_time }}
                                                -{{ $appointment_avaiable?->availableSites?->name }}
                                            </option>
                                        @endforeach --}}
                                    @endif
                                </select>
                                @error('exam_session')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <span class="text-danger">*</span>
                                <label for="" class="form-label">Exam Date</label>
                                <input type="date" class="form-control" name="exam_date" id="exam_date"
                                    value="{{ old('exam_date') ? old('exam_date') : (isset($clinic_permit) ? $clinic_permit->proposed_date : '') }}"
                                    {{ isset($clinic_permit) ? 'readonly' : '' }}>
                                @error('exam_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="text-danger">*</span>
                            <label for="" class="form-label">Application Date</label>
                            <input type="date" class="form-control" name="application_date"
                                value="{{ old('application_date') }}" max="{{ date('Y-m-d') }}">
                            @error('application_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">
                                Cancel Application
                            </a>
                            <button class="btn btn-primary" onclick="showLoading(this)" type="button">
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

            // const hamBurger = document.querySelector(".toggle-btn");

            // hamBurger.addEventListener("click", function() {
            //     document.querySelector("#sidebar").classList.toggle("expand");
            // });
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


                if ({!! json_encode(old('permit_category_id')) !!}) {
                    populateSchedule({!! json_encode(old('permit_category_id')) !!});
                }
            }
        </script>
        <script>
            function populateSchedule(permit_category_id) {
                if ({!! !isset($clinic_permit) !!}) {
                    let options = [];
                    let i = 0;
                    schedule_select = document.getElementById('exam_session');
                    schedule_select.innerHTML = "";
                    var disabled_option = document.createElement('option');
                    disabled_option.setAttribute('disabled', true);
                    disabled_option.setAttribute('selected', true);
                    disabled_option.innerHTML = "Please select an exam session";
                    schedule_select.appendChild(disabled_option);
                    {!! json_encode($appointments_available) !!}.forEach((element) => {
                        if (element['permit_category_id'] == permit_category_id) {
                            options[i] = document.createElement('option');
                            options[i].value = element["id"];
                            options[i].innerHTML = element["category_name"] + ' - ' + element['exam_day']
                                .toUpperCase() +
                                ' - ' + element['exam_start_time'] + ' - ' + element["site_name"];
                            schedule_select.appendChild(options[i]);
                            if ({!! json_encode(old('exam_session')) !!} != "") {
                                if ({!! json_encode(old('exam_session')) !!} == element["id"]) {
                                    options[i].setAttribute('selected', true);

                                }
                            }
                        }
                    })


                }
                // console.log(appointments[0]['available_sites']);
            }
            // alert();
        </script>

<script>
    const examSession = document.getElementById('exam_session');
    const examDate = document.getElementById('exam_date');

    if (examSession && examDate) {
        // Function to get the corresponding day number
        const getDayNumber = (dayOfWeek) => {
            const daysArray = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
            return daysArray.indexOf(dayOfWeek);
        };

        // Function to format the date as 'YYYY-MM-DD'
        const formatDate = (date) => {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        };

        examSession.addEventListener('change', () => {
            const selectedOption = examSession.options[examSession.selectedIndex];
            const selectedText = selectedOption.text;
            const dayOfWeek = selectedText.split('-')[1].trim();

            const targetDayNumber = getDayNumber(dayOfWeek);
            const today = new Date();
            const currentDay = today.getDay();

            let daysToAdd = targetDayNumber - currentDay;

            // If the selected day is in the past (or today), add 7 days
            if (daysToAdd <= 0) {
                daysToAdd += 7;
            }

            today.setDate(today.getDate() + daysToAdd);

            examDate.value = formatDate(today);  // Set the exam date to the next available day
        });
    }
</script>

        @include('partials.messages.loading_message')
    </div>
@endsection
