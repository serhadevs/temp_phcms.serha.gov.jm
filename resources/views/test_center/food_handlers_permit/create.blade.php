@extends('partials.layouts.layout')

@section('title', 'Enter Test Results for permit')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card mb-2">
                <div class="card-header">
                    Add Test Results
                </div>

                
                <div class="card-body mb-2">
                    <form method="POST" action="{{ route('test-results.permit.add') }}">
                        @method('POST')
                        @csrf
                       
                                    <div class="mt-3">
                                        <label for="" class="form-label">Photo</label>
            
                                        @if (empty($permit_application->photo_upload))
                                            @if ($permit_application->gender === 'Male')
                                                <img src="{{ asset('/images/male.jpg') }}" alt="Male"
                                                    style="display:block; width:15rem; height:auto;">
                                            @else
                                                <img src="{{ asset('/images/female.jpg') }}" alt="Female"
                                                    style="display:block; width:15rem; height:auto;">
                                            @endif
                                        @else
                                            <img src="{{ asset('storage/' . $permit_application->photo_upload) }}" alt="Uploaded Photo"
                                                style="display:block; width:15rem; height:auto;border:1px solid blue;">
                                        @endif
                                    </div>
                       
                        

                      
                                    <div class="mt-3">
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
                                        <input type="text" name="application_type_id" value="1" hidden>
                                        <input type="text" name="application_id" value="{{ $permit_application->id }}" hidden>
                                    </div>
            
                                    <div class="row mt-3">
                                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                            <label for="" class="form-label">First Name</label>
                                            <input type="text" class="form-control" value="{{ $permit_application->firstname }}"
                                                readonly>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                            <label for="" class="form-label">Middle Name</label>
                                            <input type="text" class="form-control" value="{{ $permit_application->middlename }}"
                                                readonly>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                            <label for="" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" value="{{ $permit_application->lastname }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                            <div class="form-label">Home Address</div>
                                            <input type="text" class="form-control" value="{{ $permit_application->address }}"
                                                readonly>
                                        </div>
            
                                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                            <div class="form-label">Business Address</div>
                                            <input type="text" class="form-control"
                                                value="{{ $permit_application->employer_address }}" readonly>
                                        </div>
            
                                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                            <div class="form-label">Date of Birth</div>
                                            <input type="date" class="form-control"
                                                value="{{ $permit_application->date_of_birth }}"readonly>
                                        </div>
                                    </div>
            
            
                                    <div class="row mt-3">
                                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                            <label for="" class="form-label">Home Phone</label>
                                            <input type="text" class="form-control" value="{{ $permit_application->home_phone }}"
                                                readonly>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-4 mb-3">
                                            <label for="" class="form-label">Work Phone</label>
                                            <input type="text" class="form-control" value="{{ $permit_application->work_phone }}"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                                            <label for="" class="form-label">Trainer(s)</label>
                                            <input type="text" class="form-control" name="staff_contact"
                                                value="{{ old('staff_contact') }}">
                                            @error('staff_contact')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6 mb-3">
                                            <label for="" class="form-label">Test Score(in %)</label>
                                            <input type="number" class="form-control" name="overall_score"
                                                value="{{ old('overall_score') }}">
                                            @error('overall_score')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-4 col-lg-6 mb-3">
                                            <label for="" class="form-label">Test Location</label>
                                            <input type="text" class="form-control"
                                                value="{{ empty($permit_application->establishmentClinics) ? $permit_application->appointment->first()?->examDate?->examSites?->name : $permit_application->establishmentClinics?->address }}"
                                                name="test_location" readonly>
                                        </div>
                                        <div class="col-sm-12 col-md-4 col-lg-6 mb-3">
                                            <label for="" class="form-label">Test Date</label>
                                            <input type="date" class="form-control"
                                                value="{{ empty($permit_application->establishmentClinics) ? $permit_application->appointment->first()?->appointment_date : $permit_application->establishmentClinics?->proposed_date }}"
                                                name="test_date" readonly>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label for="" class="form-label">Comments</label>
                                        <textarea class="form-control" name="comments">{{ old('comments') }}</textarea>
                                    </div>
                          


                       
                        
                </div>

                <div class="card-footer mb-2">
                    <button type="button" class="btn btn-primary" onclick="showLoading(this)">
                        Submit
                    </button>
                    <a class="btn btn-danger" onclick="history.back()">
                        Cancel
                    </a>
                </div>
                {{--  --}}
                </form>
            </div>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
