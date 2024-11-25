@extends('partials.layouts.layout')

@section('title', 'Create Barber/Cosmet etc')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">
                        Hair Dressers, Beauty Therapists, Cosmotologists/Barbers
                    </h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('barber-cosmet.store') }}">
                        @csrf
                        @method('POST')
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    First Name
                                </label>
                                <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('firstname')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" name="middlename" value="{{ old('middlename') }}"
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
                                <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('lastname')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Address
                                </label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('address')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                           
                        </div>
                        <div class="row align-items-center mt-3">
                            <!-- Date of Birth -->
                            <div class="col-md-6 d-flex align-items-center">
                                <label for="date_of_birth" class="form-label me-2">
                                    <span class="text-danger fw-bold">*</span> Date of Birth
                                </label>
                                <input type="date" id="date_of_birth" class="form-control" name="date_of_birth"
                                    value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d') }}" style="flex: 1;">
                                @error('date_of_birth')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        
                            <!-- Gender -->
                            <div class="col-md-6 d-flex align-items-center">
                                <label for="" class="form-label me-2">
                                    <span class="text-danger fw-bold">*</span> Gender
                                </label>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="gender_male" class="form-check-input" value="male" name="sex"
                                        {{ old('sex') == 'male' ? 'checked' : '' }}>
                                    <label for="gender_male" class="form-check-label">Male</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" id="gender_female" class="form-check-input" value="female" name="sex"
                                        {{ old('sex') == 'female' ? 'checked' : '' }}>
                                    <label for="gender_female" class="form-check-label">Female</label>
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
                                <input type="text" class="form-control" name="telephone" value="{{ old('telephone') }}"
                                    id="telephone">
                                @error('telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}"
                                    oninput="this.value=this.value.toUpperCase()">
                                @error('email')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Tax Registration Number (TRN)
                                </label>
                                <input type="text" class="form-control" name="trn" value="{{ old('trn') }}" id="trn">
                                @error('trn')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Occupation</label>
                                <input type="text" class="form-control" name="occupation"
                                    value="{{ old('occupation') }}" oninput="this.value=this.value.toUpperCase()">
                                @error('occupation')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Name of Employer</label>
                                <input type="text" class="form-control" name="employer"
                                    value="{{ old('employer') }}" oninput="this.value=this.value.toUpperCase()">
                                @error('employer')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Business Address of Employer</label>
                            <input type="text" class="form-control" name="employer_address"
                                value="{{ old('employer_address') }}" oninput="this.value=this.value.toUpperCase()">
                            @error('employer_address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3 row">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Have you ever applied for Health Certification?
                                </label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" value="1" name="applied_before"
                                        {{ old('applied_before') ? (old('applied_before') == '1' ? 'checked' : '') : '' }}>
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" value="0" name="applied_before"
                                        {{ old('applied_before') == '0' ? 'checked' : '' }}>
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
                                        {{ old('granted') ? (old('granted') == '1' ? 'checked' : '') : '' }}>
                                    <label for="" class="form-label">Granted</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="granted" value="0"
                                        {{ old('granted') ? (old('granted') == '0' ? 'checked' : '') : '' }}>
                                    <label for="" class="form-label">Refused</label>
                                </div>
                                @error('granted')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="reason" class="form-label">If Refused, state reason (20 chars min, 100 max)</label>
                            <textarea 
                                name="reason" 
                                id="reason" 
                                class="form-control" 
                                style="resize: none;" 
                                oninput="this.value=this.value.toUpperCase()">{{ old('reason') }}</textarea>
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
                        
                  
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                    <button type="button" class="btn btn-primary" onclick="showLoading(this)">
                        Submit Application
                    </button>
                </div>
            </form>
            </div>
            <script src="https://unpkg.com/imask"></script>
            <script>
                const telephone = document.getElementById('telephone');
                const trn = document.getElementById('trn');

                const maskOptions = {
                    mask: '+1(000)000-0000'
                }
                const maskOptions2 = {
                    mask: '000-000-000'
                }

                const mask1 = IMask(telephone, maskOptions);
                const mask2 = IMask(trn, maskOptions2);
            </script>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
