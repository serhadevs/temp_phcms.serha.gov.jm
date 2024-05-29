@extends('partials.layouts.layout')

@section('title', 'New Health Interviews')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-3">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">Add New Health Interview</h2>
                    <hr>
                    <form action="{{ route('health-interview.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">First Name</label>
                                <input type="text" class="form-control" value="{{ $application?->firstname }}" readonly>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Middle Name</label>
                                <input type="text" class="form-control" value="{{ $application?->middlename }}" readonly>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Last Name</label>
                                <input type="text" class="form-control" value="{{ $application?->lastname }}" readonly>
                            </div>
                        </div>
                        <div class="mt-3">
                            <input type="text" class="form-control" placeholder="application_type"
                                value="{{ $app_type_id }}" name="app_type_id" hidden>
                            @error('app_type_id')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            <input type="text" class="form-control mt-2" placeholder="application_id"
                                value="{{ $application?->id }}" name="application_id" hidden>
                            @error('application_id')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                            {{-- Determines if test reaults have been entered for this permit application --}}
                            {{-- 0 - No test results have been entered --}}
                            {{-- 1 - Test Results have been entered --}}
                            {{-- 2 - THis is a type barber and cosmetics/health ceritificate  not food halders permit' --}}
                            <input type="text" class="form-control"
                                value="{{ $app_type_id == '1' ? (empty($test_info) ? '1' : '0') : '2' }}"
                                name="test_results_exist" hidden>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Home Address</label>
                            <input type="text" class="form-control" value="{{ $application?->address }}" readonly>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Employer Address</label>
                            <input type="text" class="form-control" value="{{ $application?->employer_address }}"
                                readonly>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" value="{{ $application?->date_of_birth }}" readonly>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Cell phone</label>
                                <input type="text" class="form-control" value="{{ $application?->cell_phone }}"
                                    readonly>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Home phone</label>
                                <input type="text" class="form-control" value="{{ $application?->home_phone }}"
                                    readonly>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Work phone</label>
                                <input type="text" class="form-control" value="{{ $application?->work_phone }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Literacy
                                </label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="literate" value="1"
                                        {{ old('literate') == '1' ? 'checked' : '' }} />
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="literate" value="0"
                                        {{ old('literate') == '0' ? 'checked' : '' }}>
                                    <label for="" class="form-check-label">No</label>
                                </div>
                                @error('literate')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Have you ever had typhoid or paratyphoid?
                                </label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="typhoid" value="1"
                                        {{ old('typhoid') == '1' ? 'checked' : '' }} />
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="typhoid" value="0"
                                        {{ old('typhoid') == '0' ? 'checked' : '' }} />
                                    <label for="" class="form-check-label">No</label>
                                </div>
                                @error('typhoid')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Are you suffering from any of the following symptoms?
                                Tick if YES.</label>
                            @foreach ($symptoms as $symptom)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" value="{{ $symptom->id }}"
                                        name="symptoms[]"
                                        {{ old('symptoms') ? (in_array($symptom->id, old('symptoms')) ? 'checked' : '') : '' }}>
                                    <label for="" class="form-check-label">{{ $symptom->name }}</label>
                                </div>
                            @endforeach
                            @error('symptoms[]')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Have you ever lived abroad?
                                </label>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="lived_abroad" value="1"
                                        {{ old('lived_abroad') == '1' ? 'checked' : '' }} />
                                    <label for="" class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" class="form-check-input" name="lived_abroad" value="0"
                                        {{ old('lived_abroad') == '0' ? 'checked' : '' }} />
                                    <label for="" class="form-check-label">No</label>
                                </div>
                                @error('lived_abroad')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">If yes, where?</label>
                                <input type="text" class="form-control" name="lived_abroad_location"
                                    value="{{ old('lived_abroad_location') ? old('lived_abroad_location') : '' }}">
                                @error('lived_abroad_location')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">If yes, when?</label>
                                <input type="text" class="form-control" name="lived_abroad_date"
                                    value="{{ old('lived_abroad_date') ? old('lived_abroad_date') : '' }}">
                                @error('lived_abroad_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Have you travelled abroad recently?
                            </label>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="travel_abroad" value="1"
                                    {{ old('travel_abroad') == '1' ? 'checked' : '' }}>
                                <label for="" class="form-check-label">Yes</label>
                            </div>
                            <div class="form-check">
                                <input type="radio" class="form-check-input" name="travel_abroad" value="0"
                                    {{ old('travel_abroad') == '0' ? 'checked' : '' }}>
                                <label for="" class="form-check-label">No</label>
                            </div>
                            @error('travel_abroad')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-2">
                            <div class="row">
                                <div class="col">
                                    <label for="" class="form-label">If yes, where?</label>
                                    <input type="text" class="form-control" name="destination[]"
                                        value="{{ old('destination') ? old('destination')[0] : '' }}">
                                    @error('destination.0')
                                        <p class="text-danger">This field is required is person has travelled.</p>
                                    @enderror
                                </div>
                                <div class="col">
                                    <label for="" class="form-label">If yes, when?</label>
                                    <input type="text" class="form-control" name="travel_date[]"
                                        value="{{ old('travel_date') ? old('travel_date')[0] : '' }}">
                                    @error('travel_date.0')
                                        <p class="text-danger">This field is required is person has travelled.</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="row">
                                <div class="col">
                                    <label for="" class="form-label">If yes, where?</label>
                                    <input type="text" class="form-control" name="destination[]"
                                        value="{{ old('destination') ? (old('destination')[1] ? old('destination')[1] : '') : '' }}">
                                </div>
                                <div class="col">
                                    <label for="" class="form-label">If yes, when?</label>
                                    <input type="text" class="form-control" name="travel_date[]"
                                        value="{{ old('travel_date') ? (old('travel_date')[1] ? old('travel_date')[1] : '') : '' }}">
                                </div>
                            </div>
                        </div>
                        <h5 class="text-muted mt-3">Physical Examination (Observation)</h5>
                        <div class="row mt-2">
                            <div class="col">
                                <label for="" class="form-label">
                                    <span class="text-danger fw-bold">*</span>
                                    Whitlow
                                </label>
                                <select name="whitlow" id="" class="form-select">
                                    <option selected disabled>Select an option</option>
                                    <option value="absent" {{ old('whitlow') == 'absent' ? 'selected' : '' }}>Absent
                                    </option>
                                    <option value="present" {{ old('whitlow') == 'present' ? 'selected' : '' }}>Present
                                    </option>
                                    <option value="undetermined" {{ old('whitlow') == 'undetermined' ? 'selected' : '' }}>
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
                                <select name="hands_condition" id="" class="form-select">
                                    <option selected disabled>Select an option</option>
                                    <option value="satisfactory"
                                        {{ old('hands_condition') == 'satisfactory' ? 'selected' : '' }}>Satisfactory
                                    </option>
                                    <option value="unsatisfactory"
                                        {{ old('hands_condition') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory
                                    </option>
                                    <option value="undetermined"
                                        {{ old('hands_condition') == 'undetermined' ? 'selected' : '' }}>Undetermined
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
                                <select name="fingernails_condition" id="" class="form-select">
                                    <option selected disabled>Select an option</option>
                                    <option value="satisfactory"
                                        {{ old('fingernails_condition') == 'satisfactory' ? 'selected' : '' }}>Satisfactory
                                    </option>
                                    <option value="unsatisfactory"
                                        {{ old('fingernails_condition') == 'unsatisfactory' ? 'selected' : '' }}>
                                        Unsatisfactory</option>
                                    <option value="undetermined"
                                        {{ old('fingernails_condition') == 'undetermined' ? 'selected' : '' }}>Undetermined
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
                                <select name="teeth_condition" id="" class="form-select">
                                    <option selected disabled>Select an option</option>
                                    <option value="satisfactory"
                                        {{ old('teeth_condition') == 'satisfactory' ? 'selected' : '' }}>Satisfactory
                                    </option>
                                    <option value="unsatisfactory"
                                        {{ old('teeth_condition') == 'unsatisfactory' ? 'selected' : '' }}>Unsatisfactory
                                    </option>
                                    <option value="undetermined"
                                        {{ old('teeth_condition') == 'undetermined' ? 'selected' : '' }}>Undetermined
                                    </option>
                                </select>
                                @error('teeth_condition')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <h5 class="text-muted mt-3">Medical Examination (If Conducted)</h5>
                        <div class="row mt-2">
                            <div class="col">
                                <labal class="form-label">Test Recommended</labal>
                                <input type="text" class="form-control" name="tests_recommended"
                                    value="{{ old('tests_recommended') ? old('tests_recommended') : '' }}">
                            </div>
                            <div class="col">
                                <labal class="form-label">Results</labal>
                                <input type="number" class="form-control" name="tests_results"
                                    value="{{ old('tests_recommended') ? old('tests_results') : '' }}">
                            </div>
                        </div>
                        <h5 class="text-muted mt-3">Doctor Contact Information: </h5>
                        <div class="row mt-2">
                            <div class="col">
                                <label for="" class="form-label">Name</label>
                                <input type="text" class="form-control" name="doctor_name"
                                    value="{{ old('doctor_name') ? old('doctor_name') : '' }}">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Address</label>
                                <input type="text" class="form-control" name="doctor_address"
                                    value="{{ old('doctor_address') ? old('doctor_address') : '' }}">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Telphone Number</label>
                                <input type="text" class="form-control" name="doctor_tele"
                                    value="{{ old('doctor_tele') ? old('doctor_tele') : '' }}">
                            </div>
                        </div>
                        @if ($app_type_id == '1' && !empty($test_info))
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h5 class="text-muted">Test Results</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mt-2">
                                        <label for="" class="form-label">Trainers</label>
                                        <input type="text" class="form-control" name="staff_contact"
                                            value="{{ old('staff_contact') ? old('staff_contact') : '' }}">
                                        @error('staff_contact')
                                            <p class="text-danger">Trainers field is required.</p>
                                        @enderror
                                    </div>
                                    <div class="mt-3">
                                        <label for="" class="form-label">Overall Score</label>
                                        <input type="number" class="form-control" name="overall_score"
                                            value="{{ old('overall_score') ? old('overall_score') : '' }}">
                                        @error('overall_score')
                                            <p class="text-danger">Overall score field is required. Must be in between 0 and
                                                100.</p>
                                        @enderror
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <label for="" class="form-label">Test Location</label>
                                            <input type="text" class="form-control" name="test_location"
                                                value="{{ $application->establishment_clinic_id == '' ? $test_info->examDate?->examSites?->name : $test_info->address }}"
                                                readonly>
                                            @error('test_location')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="col">
                                            <label for="" class="form-label">Test Date</label>
                                            <input type="text" class="form-control" name="test_date"
                                                value="{{ $application->establishment_clinic_id == '' ? $test_info->appointment_date : $test_info->proposed_date }}"
                                                readonly>
                                            @error('test_date')
                                                <p class="text-danger">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label for="" class="form-label">Comments</label>
                                        <textarea class="form-control" name="comments">{{ old('comments') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <button class="btn btn-primary mt-3" type="button" onclick="showLoading(this)">
                            Submit Health Interview
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
