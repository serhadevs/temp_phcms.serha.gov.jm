@extends('partials.layouts.layout')

@section('title', 'View Application')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            @include('partials.messages.messages')
            <style>
                [class*=" bi-"]::before {
                    vertical-align: text-top !important;
                }
            </style>
            <div class="card">
                <h4 class="card-header" style="display: flex; align-items: center; justify-content: space-between;">

                    {{-- @if (app('url')->previous() === url('/advance-search/show'))
                        <a href="{{ url('/advance-search/create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    @elseif(str_contains(app('url')->previous(), 'clinic'))
                        <a href="{{ app('url')->previous() }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-arrow-left"></i>
                            Back
                        </a>
                    @else
                        <a href="{{ url('/permit/filter/0') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    @endif --}}

                    <a class="btn btn-primary"
                        href="{{ strpos(URL::previous(), 'advance-search/show') != false ? '/advance-search/create' : URL::previous() }}">
                        Back
                    </a>


                    <span>{{ $permit_application->firstname ?? 'No First Name' }}
                        {{ $permit_application->lastname ?? 'No Last Name' }}</span>
                </h4>

              

                {{-- <img src="{{ asset('storage/' . $permit->photo_upload) }}?v={{ $version }}"> --}}
                <div class="card-body">
                    <form action="{{ route('permit.application.update', ['id' => $permit_application->id]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col col-md-3 text-center">
                                <div class="mt-3 text-center">
                                    {{-- <div class="mt-3">
                                        @if ($permit_application->photo_upload)
                                            <img src="{{ asset('storage/' . $permit_application->photo_upload) }}"
                                                alt="No Image found" style="display:block" class="mx-auto rounded w-100"
                                                id="applicant_img">
                                        @endif
                                        @if (!$permit_application->photo_upload)
                                            @if (strtolower($permit_application->gender) == 'male')
                                                <img src="{{ asset('images/male.jpg') }}" class="w-100 rounded-circle" />
                                            @endif
                                            @if (strtolower($permit_application->gender) == 'female')
                                                <img src="{{ asset('images/female.jpg') }}" class="w-100 rounded-circle" />
                                            @endif


                                        @endif
                                        <input type="file" class="form-control mx-auto w-75 mt-1" id="photo_upload"
                                            name="photo_upload" style="display:none">
                                    </div> --}}

                                    <div class="mt-3">
                                        @if ($permit_application->photo_upload)
                                            @php
                                                $photoPath = storage_path(
                                                    'app/public/' . $permit_application->photo_upload,
                                                );
                                                $version = file_exists($photoPath) ? filemtime($photoPath) : 1;
                                            @endphp
                                            <img src="{{ asset('storage/' . $permit_application->photo_upload) }}?v={{ $version }}"
                                                alt="No Image found" style="display:block" class="mx-auto rounded w-100"
                                                id="applicant_img">
                                        @else
                                            @if (strtolower($permit_application->gender) == 'male')
                                                <img src="{{ asset('images/male.jpg') }}" class="w-100 rounded-circle" />
                                            @endif
                                            @if (strtolower($permit_application->gender) == 'female')
                                                <img src="{{ asset('images/female.jpg') }}" class="w-100 rounded-circle" />
                                            @endif
                                        @endif

                                        <input type="file" class="form-control mx-auto w-75 mt-1" id="photo_upload"
                                            name="photo_upload" style="display:none">
                                    </div>
                                </div>
                            </div>
                            <div class="col col-md-9">
                                {{-- <form action="{{ route('permit.application.update', ['id' => $permit_application->id]) }}" --}}
                                {{-- method="POST">
                                    @csrf
                                    @method('PUT') --}}
                                <input type="text" name="previous_url" value={{ url()->previous() }} hidden>

                                <ul class="nav nav-tabs" id="permitTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="tab-applicant-btn" data-bs-toggle="tab"
                                            data-bs-target="#tab-applicant" type="button" role="tab">Applicant
                                            Information</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-permit-btn" data-bs-toggle="tab"
                                            data-bs-target="#tab-permit" type="button" role="tab">Permit
                                            Application</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-appointment-btn" data-bs-toggle="tab"
                                            data-bs-target="#tab-appointment" type="button" role="tab">Appointment</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-test-btn" data-bs-toggle="tab"
                                            data-bs-target="#tab-test" type="button" role="tab">Test Results</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-health-btn" data-bs-toggle="tab"
                                            data-bs-target="#tab-health" type="button" role="tab">Health
                                            Interview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-messages-btn" data-bs-toggle="tab"
                                            data-bs-target="#tab-messages" type="button" role="tab">Messages</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="tab-card-btn" data-bs-toggle="tab"
                                            data-bs-target="#tab-card" type="button" role="tab">Card Info</button>
                                    </li>
                                    @if ($permit_application->editTransactions()->exists())
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="tab-transactions-btn" data-bs-toggle="tab"
                                                data-bs-target="#tab-transactions" type="button" role="tab">Transactions</button>
                                        </li>
                                    @endif
                                </ul>

                                <div class="tab-content border border-top-0 p-3 mb-3" id="permitTabContent">

                                    {{-- Applicant Information --}}
                                    <div class="tab-pane fade show active" id="tab-applicant" role="tabpanel">
                                    <div class="card border-0">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col">
                                                <label for="" class="form-label">First Name</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old('firstname') == '' ? strtoupper($permit_application->firstname) : old('firstname') }}"
                                                    disabled id="firstname" name="firstname"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                @error('firstname')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Middle Name</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old('middlename') == '' ? strtoupper($permit_application->middlename) : old('middlename') }}"
                                                    disabled id="middlename" name="middlename"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                @error('middlename')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Last Name</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old('lastname') == '' ? strtoupper($permit_application->lastname) : old('lastname') }}"
                                                    disabled id="lastname" name="lastname"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                @error('lastname')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="" class="form-label">Date of Birth</label>
                                                <input type="date" class="form-control"
                                                    value="{{ old('date_of_birth') == '' ? $permit_application->date_of_birth : old('date_of_birth') }}"
                                                    disabled id="date_of_birth" name="date_of_birth">
                                                @error('date_of_birth')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Gender</label>
                                                <select name="gender" id="gender" class="form-select" disabled>
                                                    <option disabled selected>Please select a value</option>
                                                    <option value="male"
                                                        {{ old('gender') == '' ? (strtoupper($permit_application->gender) == 'MALE' ? 'selected' : '') : (old('gender') == 'male' ? 'selected' : '') }}>
                                                        Male</option>
                                                    <option value="female"
                                                        {{ old('gender') == '' ? (strtoupper($permit_application->gender) == 'FEMALE' ? 'selected' : '') : (old('gender') == 'female' ? 'selected' : '') }}>
                                                        Female</option>
                                                </select>
                                                @error('gender')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label for="" class="form-label">Address</label>
                                            <input type="text" class="form-control"
                                                value="{{ old('address') == '' ? strtoupper($permit_application->address) : old('address') }}"
                                                disabled id="address" name="address"
                                                oninput="this.value = this.value.toUpperCase()" />
                                            @error('address')
                                                <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="" class="form-label">Cell Phone</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old('cell_phone') == '' ? $permit_application->cell_phone : old('cell_phone') }}"
                                                    disabled id="cell_phone" name="cell_phone" />
                                                @error('cell_phone')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Home Phone</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old('home_phone') == '' ? $permit_application->home_phone : old('home_phone') }}"
                                                    disabled id="home_phone" name="home_phone" />
                                                @error('home_phone')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Work Phone</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old('work_phone') == '' ? $permit_application->work_phone : old('work_phone') }}"
                                                    disabled id="work_phone" name="work_phone" />
                                                @error('work_phone')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="" class="form-label">TRN</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old('trn') == '' ? $permit_application->trn : old('trn') }}"disabled
                                                    id="trn" name="trn" />
                                                @error('trn')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Email</label>
                                                <input type="text" class="form-control"
                                                    value="{{ old('email') == '' ? strtoupper($permit_application->email) : old('email') }}"
                                                    disabled id="email" name="email"
                                                    oninput="this.value = this.value.toUpperCase()">
                                                @error('email')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="" class="form-label">Permit Category</label>
                                                <select name="permit_category_id" id="permit_cat_id" class="form-select"
                                                    disabled>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            {{ (old('permit_category_id') ? (old('permit_category_id') == $category->id ? 'selected' : '') : $category->id == $permit_application->permitCategory?->id) ? 'selected' : '' }}>
                                                            {{ $category->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('permit_category_id')
                                                    <strong class="text-danger text-bold errors">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Permit Type</label>
                                                <select name="permit_type" id="permit_type" class="form-select" disabled
                                                    onchange="showNoYears(this.value)">
                                                    <option value="regular"
                                                        {{ old('permit_type') ? (old('permit_type') == 'regular' ? 'selected' : '') : ($permit_application->permit_type == 'regular' ? 'selected' : '') }}>
                                                        REGULAR</option>
                                                    <option value="student"
                                                        {{ old('permit_type') ? (old('permit_type') == 'student' ? 'selected' : '') : ($permit_application->permit_type == 'student' ? 'selected' : '') }}>
                                                        STUDENT</option>
                                                    <option value="teacher"
                                                        {{ old('permit_type') ? (old('permit_type') == 'teacher' ? 'selected' : '') : ($permit_application->permit_type == 'teacher' ? 'selected' : '') }}>
                                                        TEACHER</option>
                                                </select>
                                                @error('permit_type')
                                                    <strong class="text-danger fw-bold">{{ $message }}</strong>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="mt-3" id="no_of_years_div"
                                            {{ (old('permit_type') == 'student' ? '' : $permit_application->permit_type != 'student') ? 'hidden' : '' }}>
                                            <label for="" class="form-label">Number of Years</label>
                                            <input type="text" class="form-control" name="no_of_years"
                                                id="no_of_years"
                                                value="{{ old('no_of_years') ? old('no_of_years') : $permit_application->no_of_years }}"
                                                disabled>
                                            @error('no_of_years')
                                                <strong class="text-danger fw-bold errors">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <div class="mt-3" style="display:none" id="reason_for_edit">
                                            <label for="" class="form-label">
                                                <span class="text-danger fw-bold">*</span>
                                                Reason for edit
                                            </label>
                                            <textarea name="edit_reason" class="form-control">{{ old('reason') }}</textarea>
                                            @error('edit_reason')
                                                <strong class="text-danger fw-bold errors">{{ $message }}</strong>
                                            @enderror
                                        </div>
                                        <input type="text" class="form-control"
                                            value="{{ isset($edit_mode) ? $edit_mode : '' }}" id="edit_mode" hidden>
                                        {{-- <input type="text" class="form-control" name="id"
                                                value="{{ $permit_application->id }}" hidden> --}}
                                        <input type="text" class="form-control" name="permit_no"
                                            value="{{ $permit_application->permit_no }}" hidden>
                                        <button class="btn btn-primary mt-3" style="display:none" id="updBtn"
                                            type="submit">
                                            <i class="bi bi-pencil-square"></i>
                                            Update Applicant Information
                                        </button>
                                        <div class="class mt-4">
                                            <a class="btn btn-warning" id="btnEdit">
                                                Edit Application
                                            </a>
                                            <a class="btn btn-danger mx-2" id="btnCancelEdit" onclick="cancelEdit()">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                    </div>
                                    </div>
                                    {{-- /Applicant Information --}}

                                    {{-- Permit Application Information --}}
                                    <div class="tab-pane fade" id="tab-permit" role="tabpanel"
                                        data-tab-url="{{ route('permit.application.view.tab.permit', ['id' => $permit_application->id]) }}">
                                        <div class="text-center py-5 tab-loading-placeholder">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /Permit Application Information --}}

                                    {{-- Appointment Information --}}
                                    <div class="tab-pane fade" id="tab-appointment" role="tabpanel"
                                        data-tab-url="{{ route('permit.application.view.tab.appointment', ['id' => $permit_application->id]) }}">
                                        <div class="text-center py-5 tab-loading-placeholder">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /Appointment Information --}}

                                    {{-- Test Results --}}
                                    <div class="tab-pane fade" id="tab-test" role="tabpanel"
                                        data-tab-url="{{ route('permit.application.view.tab.test', ['id' => $permit_application->id]) }}">
                                        <div class="text-center py-5 tab-loading-placeholder">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /Test Results --}}

                                    {{-- Health Interview Results --}}
                                    <div class="tab-pane fade" id="tab-health" role="tabpanel"
                                        data-tab-url="{{ route('permit.application.view.tab.health', ['id' => $permit_application->id]) }}">
                                        <div class="text-center py-5 tab-loading-placeholder">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /Health Interview Results --}}

                                    {{-- Messages --}}
                                    <div class="tab-pane fade" id="tab-messages" role="tabpanel"
                                        data-tab-url="{{ route('permit.application.view.tab.messages', ['id' => $permit_application->id]) }}">
                                        <div class="text-center py-5 tab-loading-placeholder">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /Messages --}}

                                    {{-- Card Info --}}
                                    <div class="tab-pane fade" id="tab-card" role="tabpanel"
                                        data-tab-url="{{ route('permit.application.view.tab.card', ['id' => $permit_application->id]) }}">
                                        <div class="text-center py-5 tab-loading-placeholder">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="visually-hidden">Loading...</span>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /Card Info --}}

                                    @if ($permit_application->editTransactions()->exists())
                                        <div class="tab-pane fade" id="tab-transactions" role="tabpanel"
                                            data-tab-url="{{ route('permit.application.view.tab.transactions', ['id' => $permit_application->id]) }}">
                                            <div class="text-center py-5 tab-loading-placeholder">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                </div>
                                {{-- /tab-content --}}

                    </form>
                    {{-- @include('food_handlers_permit.partials.collectCard') --}}
                </div>
            </div>


        </div>


    </div>
    </div>

    {{-- Payment Modal --}}
    <div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 payment-header" id="staticBackdropLabel"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="">
                        <label for="" class="form-label">Application ID</label>
                        <label for="" class="form-control" style="background:#e9ecef"
                            id="payment_ap_id"></label>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Receipt Number</label>
                        <label for="" class="form-control" style="background:#e9ecef"
                            id="payment_receipt_no"></label>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Payment Date</label>
                            <label for="" class="form-control" style="background:#e9ecef"
                                id="payment_payment_date"></label>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Appointment Date</label>
                            <label for="" class="form-control" style="background:#e9ecef"
                                id="payment_appointment_date"></label>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">Total Cost</label>
                            <label for="" class="form-control" style="background:#e9ecef"
                                id="payment_tot_cost"></label>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Amt. Paid</label>
                            <label for="" class="form-control" style="background:#e9ecef"
                                id="payment_amt_paid"></label>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Change</label>
                            <label for="" class="form-control" style="background:#e9ecef"
                                id="payment_change"></label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Close Payment
                        Info</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Payment Modal End --}}



    <script src="https://unpkg.com/imask"></script>
    <script>
        trn = document.getElementById('trn');
        const cell_phone = document.getElementById('cell_phone');
        const home_phone = document.getElementById('home_phone');
        const work_phone = document.getElementById('work_phone');

        const maskOptions = {
            mask: '000-000-000'
        }

        const maskOptions2 = {
            mask: '1(000)000-0000'
        }

        const mask = IMask(trn, maskOptions);
        const mask2 = IMask(cell_phone, maskOptions2);
        const mask3 = IMask(home_phone, maskOptions2);
        const mask4 = IMask(work_phone, maskOptions2);

        // const hamBurger = document.querySelector(".toggle-btn");

        // hamBurger.addEventListener("click", function() {
        //     document.querySelector("#sidebar").classList.toggle("expand");
        // });

        $(document).ready(
            function() {
                $("#btnEdit").click(() => {
                    allowEdit();
                });
                if (document.getElementById('edit_mode').value == "1") {
                    allowEdit();
                }
            }
        )

        window.onload = () => {
            var err = document.querySelectorAll("strong.errors");
            if (err[0]) {
                allowEdit();
            }
        }

        function showNoYears(value) {
            if (value == "student") {
                document.getElementById('no_of_years_div').removeAttribute('hidden');
            }
        }

        function allowEdit() {
            $("#firstname").removeAttr("disabled");
            $("#lastname").removeAttr("disabled");
            $("#middlename").removeAttr("disabled");
            $("#date_of_birth").removeAttr("disabled");
            $("#address").removeAttr("disabled");
            $("#cell_phone").removeAttr("disabled");
            $("#home_phone").removeAttr("disabled");
            $("#work_phone").removeAttr("disabled");
            $("#trn").removeAttr("disabled");
            $("#gender").removeAttr("disabled");
            $("#email").removeAttr("disabled");
            $('#permit_cat_id').removeAttr("disabled");
            $('#permit_type').removeAttr("disabled");
            $('#no_of_years').removeAttr("disabled");
            document.getElementById("updBtn").style.display = "";
            document.getElementById("btnEdit").style.display = "none";
            document.getElementById('reason_for_edit').style.display = "";
            // if ($("#applicant_img").attr('src') == undefined) {
            document.getElementById("photo_upload").style.display = "";
            // }
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function cancelEdit() {
            $("#firstname").attr("disabled", true);
            $("#lastname").attr("disabled", true);
            $("#middlename").attr("disabled", true);
            $("#date_of_birth").attr("disabled", true);
            $("#address").attr("disabled", true);
            $("#cell_phone").attr("disabled", true);
            $("#home_phone").attr("disabled", true);
            $("#work_phone").attr("disabled", true);
            $("#trn").attr("disabled", true);
            $("#gender").attr("disabled", true);
            $("#email").attr("disabled", true);
            $('#permit_cat_id').attr("disabled", true);
            $('#permit_type').attr("disabled", true);
            $('#no_of_years').attr("disabled", true);
            document.getElementById('edit_mode').value = "0";
            document.getElementById("updBtn").style.display = "none";
            document.getElementById("btnEdit").style.display = "";
            document.getElementById('reason_for_edit').style.display = "none";
            document.getElementById("photo_upload").style.display = "none";
        }
    </script>
    <script>
        function populatePaymentModal(payment_info, appointment_date, est_appointment_date) {
            document.querySelector('h1.payment-header').innerHTML = "Payment ID - " + payment_info['id'];
            document.getElementById('payment_ap_id').innerHTML = payment_info['application_id'];
            document.getElementById('payment_receipt_no').innerHTML = payment_info['receipt_no'];
            document.getElementById('payment_payment_date').innerHTML = new Date(payment_info['created_at'])
                .toLocaleString();
            document.getElementById('payment_appointment_date').innerHTML = est_appointment_date == "" ? appointment_date :
                est_appointment_date;
            document.getElementById('payment_tot_cost').innerHTML = payment_info['total_cost'];
            document.getElementById('payment_amt_paid').innerHTML = payment_info['amount_paid'];
            document.getElementById('payment_change').innerHTML = payment_info['change_amt'];
        }
    </script>

    {{-- Lazy-load Tab Data --}}
    <script>
        function loadPermitTab(pane) {
            if (pane.dataset.loaded === "1" || !pane.dataset.tabUrl) {
                return;
            }

            fetch(pane.dataset.tabUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    pane.innerHTML = html;
                    pane.dataset.loaded = "1";

                    // Re-run any <script> tags included in the fetched partial
                    // (innerHTML does not execute them automatically).
                    pane.querySelectorAll('script').forEach(oldScript => {
                        const newScript = document.createElement('script');
                        for (const attr of oldScript.attributes) {
                            newScript.setAttribute(attr.name, attr.value);
                        }
                        newScript.async = false;
                        newScript.textContent = oldScript.textContent;
                        oldScript.replaceWith(newScript);
                    });
                })
                .catch(() => {
                    pane.innerHTML =
                        '<div class="alert alert-danger">Failed to load this tab. Please try again.</div>';
                });
        }

        document.querySelectorAll('#permitTab button[data-bs-target]').forEach(tabButton => {
            tabButton.addEventListener('shown.bs.tab', event => {
                const pane = document.querySelector(event.target.getAttribute('data-bs-target'));
                if (pane) {
                    loadPermitTab(pane);
                }
            });
        });
    </script>

    {{-- Resend Email Javascript --}}

    <script>
        function populateResendEmailModal(message, permitApplication) {
            document.getElementById('modalHeader').innerHTML = message.emailtypes.name + ' sent to ' + permitApplication[
                'firstname'] + ' ' + permitApplication['lastname']
            document.getElementById('message_type').innerHTML = message.emailtypes.name ? message.emailtypes.name : 'N/A';
            document.getElementById('status').innerHTML = message.status.toUpperCase()
            document.getElementById('sent_at').innerHTML = message.user.firstname + " " + message.user.lastname
            document.getElementById('sent_by').innerHTML = new Date(message.sent_at).toLocaleDateString('en-GB', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }
    </script>

    <script>
        function addAppointment(avaiable_appointments, permit_application_id) {
            swal.fire({
                icon: 'question',
                title: "Add Appointment to Permit Application",
                text: "Please enter date of appointment",
                input: 'date',
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                showConfirmButton: true,
                cancelButtonText: "Cancel",
                confirmButtonText: "Continue"
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire({
                        icon: 'question',
                        title: 'Add Appointment to Permit Application',
                        text: 'Select exam session',
                        input: 'select',
                        inputAttributes: {
                            required: true
                        },
                        inputOptions: avaiable_appointments,
                        showCancelButton: true,
                        showConfirmButton: true,
                        cancelButtonText: "Cancel",
                        confirmButtonText: "Continue"
                    }).then((result2) => {
                        if (result2.isConfirmed) {
                            swal.fire({
                                icon: 'warning',
                                title: "Are you sure you want to add appointment",
                                showCancelButton: true,
                                showConfirmButton: true,
                                cancelButtonText: "No, Cancel",
                                confirmButtonText: "Yes, Confirm"
                            }).then((result3) => {
                                if (result3.isConfirmed) {
                                    $.post({!! json_encode(url('/permit/application/appointment/create')) !!}, {
                                        _method: "POST",
                                        data: {
                                            appointment_date: result.value,
                                            permit_application_id: permit_application_id,
                                            exam_date_id: result2.value
                                        },
                                        _token: "{{ csrf_token() }}"
                                    }).then((end_result) => {
                                        if (end_result[0] == 'success') {
                                            swal.fire(
                                                "Done!",
                                                end_result[1],
                                                "success").then(
                                                esc => {
                                                    if (esc) {
                                                        location
                                                            .reload();
                                                    }
                                                });
                                        } else {
                                            swal.fire(
                                                "Oops! Something went wrong.",
                                                end_result,
                                                "error");
                                        }
                                    })
                                }
                            })
                        }
                    })
                }
            })
        }
    </script>
    <script>
        function requestSignoffReversal(permit_id) {
            swal.fire({
                title: "What is your reason for requesting a reverse of this sign off?",
                text: "Reason will be recorded",
                icon: 'question',
                input: 'textarea',
                inputAttributes: {
                    required: true
                },
                showConfirmButton: true,
                showCancelButton: true,
                confirmButtonText: "Log Reason"
            }).then((result) => {
                if (result.isConfirmed) {
                    swal.fire({
                        icon: 'warning',
                        title: 'Are you sure you want to make this request?',
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonText: "Request Reversal"
                    }).then((result2) => {
                        if (result2.isConfirmed) {
                            $.post({!! json_encode(url('/sign-off/request/reversal')) !!}, {
                                _method: "POST",
                                data: {
                                    reason: result.value,
                                    application_id: permit_id,
                                    app_type: 1
                                },
                                _token: "{{ csrf_token() }}"
                            }).then(function(data) {
                                if (data[0] == "success") {
                                    swal.fire(
                                        "Done!",
                                        data[1],
                                        "success").then(
                                        esc => {
                                            if (esc) {
                                                location
                                                    .reload();
                                            }
                                        });
                                } else {
                                    swal.fire(
                                        "Oops! Something went wrong.",
                                        data,
                                        "error");
                                }
                            })
                        }
                    })
                }
            })
        }
    </script>

    <script>
        function populateCardPickUpModal(appid, payment) {
            document.getElementById('card_app_id').value = appid
            document.getElementById('application_type_id').value = payment.application_type_id

        }
    </script>


    {{-- Resend Email Javascript --}}
    @include('partials.messages.loading_message')
    </div>
@endsection
