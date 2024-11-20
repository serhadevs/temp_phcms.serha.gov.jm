@extends('partials.layouts.layout')

@section('title', 'View Application')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            @include('partials.messages.messages')
            <div class="card">
                <h4 class="card-header" style="display: flex; align-items: center; justify-content: space-between;">

                    @if (app('url')->previous() === url('/advance-search/show'))
                        <a href="{{ url('/advance-search/create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    @else
                        <a href="{{ url('/permit/filter/0') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-arrow-left"></i> Back
                        </a>
                    @endif

                    <span>{{ $permit_application->firstname . ' ' . $permit_application->lastname }}</span>
                </h4>

                <div class="card-body">
                    <form action="{{ route('permit.application.update', ['id' => $permit_application->id]) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col col-md-3 text-center">
                                <div class="mt-3 text-center">
                                    <div class="mt-3">
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
                                    </div>
                                    <div class="card mt-2">
                                        <h5 class="card-header text-muted">
                                            Test Results
                                        </h5>
                                        <div class="card-body">


                                            @if (!empty($permit_application->testResults))
                                                <ul class="list-group">
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Exam Site
                                                        <span class="badge text-bg-primary rounded-pill text-wrap"
                                                            style="white-space: normal;">
                                                            {{ $permit_application->testResults?->test_location }}
                                                        </span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Trainer(s)
                                                        <span class="badge text-bg-primary rounded-pill">
                                                            {{ $permit_application->testResults?->staff_contact }}</span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Test Score
                                                        <span class="badge text-bg-primary rounded-pill">
                                                            {{ $permit_application->testResults?->overall_score }}</span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Comments
                                                        <span class="badge text-bg-primary rounded-pill">
                                                            {{ $permit_application->testResults?->comments }}</span>
                                                    </li>
                                                </ul>
                                            @else
                                                No Test Results Available
                                            @endif
                                        </div>
                                    </div>
                                    <div class="card mt-2">
                                        <h5 class="card-header text-muted">
                                            Health Interview Results
                                        </h5>
                                        <div class="card-body">
                                            @if (!empty($permit_application->healthInterviews))
                                                <ul class="list-group">
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Literacy
                                                        <span
                                                            class="badge text-bg-{{ $permit_application->healthInterviews?->literate == '1' ? 'success' : 'danger' }}">
                                                            {{ $permit_application->healthInterviews?->literate == '1' ? 'YES' : 'NO' }}
                                                        </span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Typhiod
                                                        <span
                                                            class="badge text-bg-{{ $permit_application->healthInterviews?->typhoid == '1' ? 'success' : 'danger' }}">
                                                            {{ $permit_application->healthInterviews?->typhoid == '1' ? 'YES' : 'NO' }}
                                                        </span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Whitlow
                                                        <span
                                                            class="badge text-bg-{{ $permit_application->healthInterviews?->whitlow == 'absent' ? 'success' : 'danger' }}">
                                                            {{ strtoupper($permit_application->healthInterviews?->whitlow) }}
                                                        </span>
                                                    </li>

                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Hands
                                                        <span
                                                            class="badge text-bg-{{ $permit_application->healthInterviews?->hands_condition == 'satisfactory' ? 'success' : 'danger' }}">
                                                            {{ strtoupper($permit_application->healthInterviews?->hands_condition) }}
                                                        </span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Fingernails
                                                        <span
                                                            class="badge text-bg-{{ $permit_application->healthInterviews?->fingernails_condition == 'satisfactory' ? 'success' : 'danger' }}">
                                                            {{ strtoupper($permit_application->healthInterviews?->fingernails_condition) }}
                                                        </span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Teeth
                                                        <span
                                                            class="badge text-bg-{{ $permit_application->healthInterviews?->teeth_condition == 'satisfactory' ? 'success' : 'danger' }}">
                                                            {{ strtoupper($permit_application->healthInterviews?->teeth_condition) }}
                                                        </span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Lived Abroad
                                                        <span
                                                            class="badge text-bg-{{ $permit_application->healthInterviews?->lived_abroad == '1' ? 'success' : 'danger' }}">
                                                            {{ $permit_application->healthInterviews?->lived_abroad == '1' ? 'YES' : 'NO' }}
                                                        </span>
                                                    </li>

                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Travelled Abroad
                                                        <span
                                                            class="badge text-bg-{{ $permit_application->healthInterviews?->travel_abroad == '1' ? 'success' : 'danger' }}">
                                                            {{ $permit_application->healthInterviews?->travel_abroad == '1' ? 'YES' : 'NO' }}
                                                        </span>
                                                    </li>
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        Symptoms
                                                        @foreach ($permit_application->healthInterviews->healthInterviewSymptom as $symp)
                                                            {{ $symp->symptoms?->name }}<br />
                                                        @endforeach
                                                    </li>

                                                </ul>
                                            @else
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    No Health Interview Information Available
                                                </li>

                                            @endif
                                        </div>
                                    </div>


                                    <div class="card mt-2">
                                        <h5 class="card-header text-muted">
                                            Messages
                                        </h5>
                                        @if (!$permit_application->email)
                                            <div class="card-body">
                                                No Email Address for {{ $permit_application->firstname }}
                                                {{ $permit_application->lastname }}
                                            </div>
                                        @elseif($permit_application->messages->isEmpty())
                                            <div class="card-body">
                                                No Messages Sent to {{ $permit_application->firstname }}
                                                {{ $permit_application->lastname }}
                                            </div>
                                        @else
                                            <div class="card-body">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm nowrap table-responsive"
                                                        style="max-width: 100%">
                                                        <thead>
                                                            <tr>
                                                                <th>Type</th>
                                                                {{-- <th>Status</th> --}}
                                                                <th>View</th>
                                                                <th>Resend</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach ($permit_application->messages as $message)
                                                                <tr>
                                                                    <td><span
                                                                            class="badge text-bg-primary">{{ strtoupper($message->emailtypes->name) ?? 'N/A' }}</span>
                                                                    </td>
                                                                    {{-- <td><span
                                                                            class = "badge text-bg-success">{{ strtoupper($message->status) }}</span>
                                                                    </td>
                                                                    <td> <span
                                                                            class="badge text-bg-primary">{{ \Carbon\Carbon::parse($message->sent_at)->format('d F y') }}</span>
                                                                    </td> --}}

                                                                    <td><button type="button" class="btn btn-primary"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#exampleModal"
                                                                            onclick="populateResendEmailModal({{ json_encode($message) }}, {{ json_encode($permit_application) }})">
                                                                            <i class="bi bi-eye"></i>
                                                                        </button></td>
                                                                    <td>
                                                                        @if ($message->emailtypes->name === 'Payment')
                                                                            <a href="#"></a>
                                                                        @else
                                                                            <a href="{{ url('/messaging/resend', ['id' => $message->permit_application_id]) }}"
                                                                                class="btn btn-primary"><i
                                                                                    class="bi bi-envelope-arrow-up"></i></a>
                                                                        @endif


                                                                    </td>
                                                                </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                                @include('partials.modals.modal')
                                            </div>

                                        @endif
                                    </div>

                                    <div class="card mt-2">
                                        <h5 class="card-header text-muted">
                                            Printed Card Information
                                        </h5>

                                        @if ($permit_application->printedcard && $permit_application->printedcard?->created_at)
                                            <div class="card-body">
                                                The Card was printed on
                                                {{ \Carbon\Carbon::parse($permit_application->printedcard->created_at)->format('d F Y') }}
                                            </div>
                                        @elseif($permit_application->signOffs && $permit_application->signOffs?->created_at)
                                            <div class="card-body">
                                                The Card was signed off but not yet printed.
                                            </div>
                                        @else
                                            <div class="card-body">
                                                No Card Information is available
                                            </div>
                                        @endif
                                    </div>
                                    {{-- Card Collected --}}




                                    <div class="card mt-2">
                                        <h5 class="card-header text-muted">
                                            Permit Processing Tracker
                                        </h5>

                                        <div class="card-body">
                                            <ul class="list-group">
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Days between Application and Appointment Date
                                                    <span class="badge bg-primary rounded-pill">

                                                        @if ($permit_application->establishmentClinics)
                                                            {{ \Carbon\Carbon::parse($permit_application->establishmentClinics->proposed_date)->diffInDays(\Carbon\Carbon::parse($permit_application->created_at)) }}
                                                        @elseif($permit_application->appointment->isNotEmpty())
                                                            {{ \Carbon\Carbon::parse($permit_application->application_date)->diffInDays(\Carbon\Carbon::parse($permit_application->appointment->first()->appointment_date)) }}
                                                        @else
                                                            0
                                                        @endif


                                                    </span>
                                                </li>
                                                {{-- <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Days between Test Completed and Test Score Uploaded
                                                    <span class="badge bg-primary rounded-pill">

                                                        @if ($permit_application->establishmentClinics)
                                                            {{ \Carbon\Carbon::parse($permit_application->establishmentClinics->proposed_date)->diffInDays(\Carbon\Carbon::parse($permit_application->testResults->created_at)) }}
                                                        @elseif(
                                                            $permit_application->testResults &&
                                                                $permit_application->testResults->created_at &&
                                                                $permit_application->appointment->isNotEmpty())
                                                            {{ \Carbon\Carbon::parse($permit_application->appointment->first()->appointment_date)->diffInDays(\Carbon\Carbon::parse($permit_application->testResults->created_at)) }}
                                                        @else
                                                            0
                                                        @endif

                                                    </span>

                                                </li> --}}

                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Days between Test Completed and Test Score Uploaded
                                                    <span class="badge bg-primary rounded-pill">
                                                        @if ($permit_application->testResults && $permit_application->testResults->created_at)
                                                            @if ($permit_application->establishmentClinics && $permit_application->establishmentClinics->proposed_date)
                                                                {{ \Carbon\Carbon::parse($permit_application->establishmentClinics->proposed_date)->diffInDays(\Carbon\Carbon::parse($permit_application->testResults->created_at)) }}
                                                            @elseif (
                                                                $permit_application->appointment &&
                                                                    $permit_application->appointment->isNotEmpty() &&
                                                                    $permit_application->appointment->first()->appointment_date)
                                                                {{ \Carbon\Carbon::parse($permit_application->appointment->first()->appointment_date)->diffInDays(\Carbon\Carbon::parse($permit_application->testResults->created_at)) }}
                                                            @else
                                                                0
                                                            @endif
                                                        @else
                                                            0
                                                        @endif
                                                    </span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Days between Test Completed and Medical Interview
                                                    <span class="badge bg-primary rounded-pill">

                                                        @if (
                                                            $permit_application->healthInterviews &&
                                                                $permit_application->healthInterviews?->created_at &&
                                                                $permit_application->appointment->isNotEmpty())
                                                            {{ \Carbon\Carbon::parse($permit_application->appointment->first()->appointment_date)->diffInDays(\Carbon\Carbon::parse($permit_application->healthInterviews?->created_at)) }}
                                                        @else
                                                            0
                                                        @endif
                                                    </span>
                                                </li>
                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Days between Test Completed and Sign Off Completed
                                                    <span class="badge bg-primary rounded-pill">

                                                        @if ($permit_application->establishmentClinics)
                                                            {{ \Carbon\Carbon::parse($permit_application->establishmentClinics->proposed_date)->diffInDays(\Carbon\Carbon::parse($permit_application->signOffs?->created_at)) }}
                                                        @elseif (
                                                            $permit_application->signOffs &&
                                                                $permit_application->signOffs?->created_at &&
                                                                $permit_application->appointment->isNotEmpty())
                                                            {{ \Carbon\Carbon::parse($permit_application->appointment[0]->appointment_date)->diffInDays(\Carbon\Carbon::parse($permit_application->signOffs?->created_at)) }}
                                                        @else
                                                            0
                                                        @endif
                                                    </span>
                                                </li>
                                                {{-- <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Days Between Test Completion and Card Printing --}}
                                                {{-- <span class="badge bg-primary rounded-pill">
                                                        @if ($permit_application->printedcard && $permit_application->printedcard?->created_at)
                                                            {{ \Carbon\Carbon::parse($permit_application->appointment[0]->appointment_date)->diffInDays(\Carbon\Carbon::parse($permit_application->printedcard?->created_at)) }}
                                                        @else
                                                            0
                                                        @endif
                                                    </span> --}}
                                                {{-- </li> --}}

                                                <li
                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                    Total Days from Application to Printing of Card
                                                    <span class="badge bg-primary rounded-pill">
                                                        @if ($permit_application->printedcard && $permit_application->printedcard?->created_at)
                                                            {{ \Carbon\Carbon::parse($permit_application->application_date)->diffInDays(\Carbon\Carbon::parse($permit_application->printedcard?->created_at)) }}
                                                        @else
                                                            0
                                                        @endif
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>

                                    </div>





                                </div>
                            </div>
                            <div class="col col-md-9">
                                {{-- <form action="{{ route('permit.application.update', ['id' => $permit_application->id]) }}" --}}
                                {{-- method="POST">
                                    @csrf
                                    @method('PUT') --}}
                                <div class="card">
                                    <h4 class="text-muted card-header">
                                        Applicant Information
                                    </h4>
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
                                                <select name="permit_type" id="permit_type" class="form-select" disabled>
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
                                    </div>
                                </div>

                                {{-- </form> --}}

                                <div class="card mt-3">
                                    <h4 class="text-muted card-header">
                                        Application Information
                                    </h4>
                                    <div class="card-body">
                                        {{-- <div class="mt-3"> --}}
                                        <div class="row">
                                            <div class="col">
                                                <label for="" class="form-label">Application Number</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $permit_application->id }}" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Permit Number</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $permit_application->permit_no }}" disabled>
                                            </div>
                                        </div>
                                        {{-- <div class="row mt-3"> --}}
                                        {{-- <div class="col">
                                                    <label for="" class="form-label">Permit Type</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ strtoupper($permit_application->permit_type) }}"
                                                        disabled>
                                                </div>
                                                <div class="col">
                                                    <label for="" class="form-label">Permit Category</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ strtoupper($permit_application->permitCategory?->name) }}"
                                                        disabled>
                                                </div> --}}
                                        {{-- </div> --}}
                                        {{-- @if(\Carbon\Carbon::parse(optional($permit_application->signOffs)->expiry_date)->isPast())
                                                <div class="mt-3">
                                                    <div class="alert alert-danger" role="alert">
                                                        Card has Expired on {{\Carbon\Carbon::parse(optional($permit_application->signOffs)->expiry_date)->format('d F Y') }}
                                                      </div>
                                                </div>
                                        @else --}}
                                        <div class="mt-3">
                                            <label for="" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control"
                                                value="{{ !empty($permit_application->signOffs) ? $permit_application->signOffs?->expiry_date : '' }}"
                                                disabled>
                                        </div>
                                        {{-- @endif --}}
                                       
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="" class="form-label">Granted</label>
                                                <input type="text" class="form-control"
                                                    value="{{ strtoupper($permit_application->granted == 1 ? 'Granted' : ($permit_application->granted == 0 ? 'Not Granted' : 'N/A')) }}"
                                                    disabled>
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Sign Off Status</label>
                                                <input type="text" class="form-control"
                                                    value="{{ strtoupper($permit_application->sign_off_status == 1 ? 'Signed Off' : 'Not Signed Off') }}"
                                                    disabled>
                                            </div>
                                            @if ($permit_application->sign_off_status == 1)
                                                <div class="col">
                                                    <label for="" class="form-label">Signed Off By</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ strtoupper(optional($permit_application->signOffs?->user)->firstname) }} {{ strtoupper(optional($permit_application->signOffs?->user)->lastname) }}"
                                                        disabled>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-3">
                                                <label for="" class="form-label">Applied Before</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $permit_application->applied_before == 1 ? 'YES' : 'NO' }}"
                                                    disabled>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="" class="form-label">Establishment</label>
                                                <input type="text" class="form-control"
                                                    value="{{ strtoupper(empty($permit_application->establishmentClinics) ? '' : $permit_application->establishmentClinics?->name) }}"
                                                    disabled>
                                            </div>
                                            <div class="col">
                                                <div class="row">
                                                    <div
                                                        class="col {{ !empty($permit_application->payment) ? 'col-md-7' : '' }}">
                                                        <label for="" class="form-label">Payment Status</label>
                                                        <input type="text" class="form-control"
                                                            value="{{ empty($permit_application->payment) ? 'NOT PAID' : 'PAID' }}"
                                                            disabled>
                                                    </div>
                                                    @if (!empty($permit_application->payment))
                                                        <div class="col col-md-5 mx-auto" style="align-self:end">
                                                            <button class="btn btn-success" style="align-items:center"
                                                                type="button" data-bs-toggle="modal"
                                                                data-bs-target="#staticBackdrop2"
                                                                onclick="populatePaymentModal({{ json_encode($permit_application->payment) }}, {{ json_encode($permit_application?->appointment?->first()?->appointment_date) }}, {{ json_encode(!empty($permit_application->establishmentClinics) ? $permit_application->establishmentClinics?->proposed_date : '') }} )">
                                                                <i class="bi bi-coin fs-6"></i>
                                                                View Payment
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="" class="form-label">Added By</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $permit_application->user?->firstname . ' ' . $permit_application->user?->lastname }}"
                                                    disabled>
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Application Date</label>
                                                <input type="text" class="form-control"
                                                    value="{{ \Carbon\Carbon::parse($permit_application->application_date)->format('d F Y') }}"
                                                    disabled>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label for="" class="form-label">Reason for refusal (if
                                                any)</label>
                                            <textarea class="form-control" disabled>{{ $permit_application->reason }}</textarea>
                                        </div>
                                        {{-- </div> --}}
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <div class="card">
                                        <h4 class="text-muted card-header">Appointment Info</h4>
                                        <div class="card-body">
                                            @include('partials.tables.permit_applications_appointments_table')
                                        </div>
                                    </div>
                                </div>
                                @if (!empty($permit_application->editTransactions))
                                    <div class="card mt-3">
                                        <div class="card-header">
                                            <h4 class="text-muted">
                                                Transactions
                                            </h4>
                                        </div>
                                        <div class="card-body">
                                            @include('partials.tables.edit_transactions_table')
                                        </div>
                                    </div>
                                @endif

                                <div class="class mt-4">
                                    <a class="btn btn-warning" id="btnEdit">
                                        Edit Application
                                    </a>
                                    <a class="btn btn-danger mx-2" onclick="history.back()">
                                        Cancel
                                    </a>
                                </div>


                    </form>
                    {{-- Card Collected --}}

                    <div class="card mt-3">
                        <h5 class="card-header text-muted">
                            Card Pickup Details
                        </h5>

                        @if ($permit_application->printedcard && $permit_application->signOffs->expiry_date > \Carbon\Carbon::now())
                            <div class="card-body">
                                Card is ready for pickup
                            </div>

                            <div class="card-footer">
                                <button type="button" class="btn btn-success mt-1"
                                    data-bs-toggle="modal"onclick="populateCardPickUpModal({{ json_encode($permit_application->id) }}, {{ json_encode($permit_application->payment) }})"
                                    data-bs-target="#cardModal">Enter Pickup Details</button>
                            </div>
                            @include('partials.modals.addCardInfoModal')
                        @elseif($permit_application->collected_cards)
                            <div class="card-body">
                                Card was collected by
                                {{ $permit_application->collected_cards?->collected_by }} on
                                {{ \Carbon\Carbon::parse($permit_application->collected_cards?->created_at)->format('d F Y') }}
                            </div>
                        @elseif(!$permit_application->collected_card)
                            <div class="card-body">
                                Card was not picked up. 
                            </div>
                        @endif

                    </div>
                    {{-- Card Collected --}}
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
            document.getElementById("updBtn").style.display = "";
            document.getElementById('reason_for_edit').style.display = "";
            // if ($("#applicant_img").attr('src') == undefined) {
            document.getElementById("photo_upload").style.display = "";
            // }
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
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
        function populateCardPickUpModal(appid, payment) {
            document.getElementById('card_app_id').value = appid
            document.getElementById('application_type_id').value = payment.application_type_id
        }
    </script>


    {{-- Resend Email Javascript --}}
    @include('partials.messages.loading_message')
    </div>
@endsection
