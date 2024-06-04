@extends('partials.layouts.layout')

@section('title', 'Renew Health Certificate Application')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Renew Barber/Cosmet Application {{ $application->firstname }} {{ $application->lastname }}
                    </h2>
                    <hr>
                    <form method="POST" action="{{ route('barber-cosmet.application.renew', ['id' => $application->id]) }}">
                        @csrf
                        @method('POST')
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    First Name
                                </label>
                                <input type="text" class="form-control" name="firstname"
                                    value="{{ old('firstname') ? old('firstname') : $application->firstname }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('firstname')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middlename"
                                    value="{{ old('middlename') ? old('middlename') : $application->middlename }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('middlename')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Last Name
                                </label>
                                <input type="text" class="form-control" name="lastname"
                                    value="{{ old('lastname') ? old('lastname') : $application->lastname }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('lastname')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Address
                            </label>
                            <input type="text" class="form-control" name="address"
                                value="{{ old('address') ? old('address') : $application->address }}"
                                oninput="this.value=this.value.toUpperCase()">
                            @error('address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Birth Date
                                </label>
                                <input type="date" class="form-control" name="date_of_birth"
                                    value="{{ old('date_of_birth') ? old('date_of_birth') : $application->date_of_birth }}">
                                @error('date_of_birth')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Gender
                                </label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" value="male" name="sex"
                                        {{ old('sex') ? (old('sex') == 'male' ? 'checked' : '') : ($application->sex ? ($application->sex == 'male' ? 'checked' : '') : '') }}>
                                    <label for="" class="form-check-label">Male</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" value="female" name="sex"
                                        {{ old('sex') ? (old('sex') == 'female' ? 'checked' : '') : ($application->sex ? ($application->sex == 'female' ? 'checked' : '') : '') }}>
                                    <label for="" class="form-check-label">Female</label>
                                </div>
                                @error('sex')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 row">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Telephone
                                </label>
                                <input type="text" class="form-control" name="telephone"
                                    value="{{ old('telephone') ? old('telephone') : $application->telephone }}">
                                @error('telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email"
                                    value="{{ old('email') ? old('email') : $application->email }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Tax Registration Number (TRN)
                            </label>
                            <input type="text" class="form-control" name="trn"
                                value="{{ old('trn') ? old('trn') : $application->trn }}">
                            @error('trn')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Occupation</label>
                                <input type="text" class="form-control" name="occupation"
                                    value="{{ old('occupation') ? old('occupation') : $application->occupation }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('occupation')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Name of Employer</label>
                                <input type="text" class="form-control" name="employer"
                                    value="{{ old('employer') ? old('employer') : $application->employer }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('employer')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Business Address of Employer</label>
                            <input type="text" class="form-control" name="employer_address"
                                value="{{ old('employer_address') ? old('employer_address') : '' }}"
                                oninput="this.value=this.value.toUpperCase()">
                            @error('employer_address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3 row">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Have you ever applied for HealthCertification?
                                </label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" value="1" name="applied_before"
                                        checked>
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" value="0" name="applied_before"
                                        disabled>
                                    <label for="" class="form-check-label">No</label>
                                </div>
                                @error('applied_before')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Was the application granted or refused?
                                </label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="granted" value="1"
                                        {{ old('granted') ? (old('granted') == '1' ? 'checked' : '') : ($application->granted == '1' ? 'checked' : '') }}>
                                    <label for="" class="form-label">Granted</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="granted" value="0"
                                        {{ old('granted') ? (old('granted') == '0' ? 'checked' : '') : ($application->granted == '0' ? 'checked' : '') }}>
                                    <label for="" class="form-label">Refused</label>
                                </div>
                                @error('granted')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">If Reused, state reason(20 chars min, 100
                                max)</label>
                            <textarea name="reason" class="form-control" oninput="this.value=this.value.toUpperCase()">{{ old('reason') ? old('reason') : $application->reason }}</textarea>
                            @error('reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Schedule Appointment
                                </label>
                                <select name="exam_date_id" id="" class="form-select">
                                    <option selected disabled class="text-center">-----------------Select a
                                        session-----------------</option>
                                    @foreach ($exam_sessions as $session)
                                        <option value="{{ $session->id }}"
                                            {{ old('exam_date_id') ? (old('exam_date_id') == $session->id ? 'selected' : '') : '' }}>
                                            {{ strtoupper($session?->exam_day . ' - ' . $session?->exam_start_time . ' - ' . $session?->availableSites?->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('exam_date_id')
                                    <p class="text-danger">This is a required field</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Appointment Date
                                </label>
                                <input type="date" name="appointment_date" class="form-control"
                                    value="{{ old('appointment_date') }}">
                                @error('appointment_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Application Date
                            </label>
                            <input type="date" class="form-control" name="application_date"
                                value="{{ old('application_date') }}">
                            @error('application_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">
                            Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
