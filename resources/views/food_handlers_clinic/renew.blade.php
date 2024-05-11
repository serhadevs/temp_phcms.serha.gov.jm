@extends('partials.layouts.layout')

@section('title', 'Renew Establishment Clinic')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">Renew Clinic {{ $application->name }}</h2>
                </div>
                <div class="progress mx-5 mt-3" role="progressbar" aria-label="Example with label" aria-valuenow="25"
                    aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar" style="width:0%"></div>
                </div>
                <div class="card-body">
                    <form action="{{ route('food-handlers-clinics.renew', ['id' => $application->id]) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="card" id="establishment_info">
                            <div class="card-body">
                                <h4 class="text-muted">
                                    Establishment Clinic Information
                                </h4>
                                <hr>
                                <div class="mt-3">
                                    <label for="" class="form-label">
                                        <span class="text-danger fw-bold">*</span>
                                        Establishment Name
                                    </label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ old('name') ? old('name') : $application?->name }}"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <label for="" class="form-label">
                                        <span class="text-danger fw-bold">*</span>
                                        Address
                                    </label>
                                    <input type="text" class="form-control" name="address"
                                        value="{{ old('address') ? old('address') : $application->address }}"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('address')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <label for="" class="form-label">
                                            <span class="text-danger fw-bold">*</span>
                                            Telephone Number
                                        </label>
                                        <input type="text" class="form-control" name="telephone"
                                            value="{{ old('telephone') ? old('telephone') : $application->telephone }}"
                                            id="telephone">
                                        @error('telephone')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="" class="form-label">Fax Number</label>
                                        <input type="text" name="fax_no" class="form-control"
                                            value="{{ old('fax_no') ? old('fax_no') : $application->fax_no }}"
                                            id="fax_no">
                                        @error('fax_no')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="" class="form-label">
                                        <span class="text-danger fw-bold">*</span>
                                        Contact Person
                                    </label>
                                    <input type="text" class="form-control" name="contact_person"
                                        value="{{ old('contact_person') ? old('contact_person') : $application->contact_person }}"
                                        oninput="this.value = this.value.toUpperCase()">
                                    @error('contact_person')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mt-3">
                                    <label for="" class="form-label">
                                        <span class="text-danger fw-bold">*</span>
                                        No. of Emloyees
                                    </label>
                                    <input type="text" class="form-control" name="no_of_employees"
                                        value="{{ old('no_of_employees') ? old('no_of_employees') : $application->no_of_employees }}">
                                    @error('no_of_employees')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                        <label for="" class="form-label">
                                            <span class="text-danger fw-bold">*</span>
                                            Proposed Exercise Date
                                        </label>
                                        <input type="date" class="form-control" name="proposed_date"
                                            value="{{ old('proposed_date') }}">
                                        @error('proposed_date')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col">
                                        <label for="" class="form-label">
                                            <span class="text-danger fw-bold">*</span>
                                            Proposed Exercise Time
                                        </label>
                                        <input type="text" class="form-control" name="proposed_time"
                                            value="{{ old('proposed_time') }}"
                                            oninput="this.value = this.value.toUpperCase()">
                                        @error('proposed_time')
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
                                <input type="hidden" class="form-control" id="renewable_permits"
                                    name="renewable_permits" onchange="submittable(this.value)"
                                    value="{{ old('renewable_permits') ? old('renewable_permits') : '' }}">
                                @error('renewable_permits')
                                    <p class="text-danger">At least one permit has to be selected.</p>
                                @enderror
                            </div>
                        </div>
                        <div class="card" style="display:none" id="permits_info">
                            <div class="card-body">
                                <h4 class="text-muted">
                                    Individual Food Handlers Applications
                                </h4>
                                <h6 class="text-danger">
                                    *Please note that the select all checkbox only applies to page you are currently on.
                                </h6>
                                <hr>
                                @include('partials.tables.food_clinic_renewal_table')
                            </div>
                        </div>
                        <button class="btn btn-warning mt-3 shadow" onclick="changePage(this)" type="button">
                            Next Section of Renewal
                        </button>
                        <button type="button" class="btn btn-primary mt-3" id="false-submit" style="display:none">
                            Submit Renewal
                        </button>
                        <button id="submit-btn" type="submit" style="display:none" onclick="showLoading(this)">
                            Submit
                        </button>
                    </form>
                </div>
            </div>
            @foreach ($permit_applications as $application)
                <div class="modal fade" id="staticBackdrop-{{ $application->id }}" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">{{ $application->id }} -
                                    {{ $application->firstname }} {{ $application->lastname }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col col-md-4 col-sm-12">
                                        <img src="{{ asset('storage/' . $application->photo_upload) }}"
                                            alt="No Image found" style="display:block" class="mx-auto rounded w-100"
                                            id="applicant_img">
                                    </div>
                                    <div class="col col-md-8 col-sm-12">
                                        <div>
                                            <label for="" class="form-label">Permit No</label>
                                            <input type="text" class="form-control"
                                                value="{{ $application->permit_no }}" disabled>
                                        </div>
                                        <div class="mt-3">
                                            <label for="" class="form-label">Permit Category</label>
                                            <input type="text" class="form-control"
                                                value="{{ $application->permitCategory?->name }}" disabled>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="" class="form-label">Date of Birth</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $application->date_of_birth }}" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Gender</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $application->gender }}" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">TRN</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $application->trn }}" disabled>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <label for="" class="form-label">Permit Type</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $application->permit_type }}" disabled>
                                            </div>
                                            <div class="col">
                                                <label for="" class="form-label">Application Date</label>
                                                <input type="text" class="form-control"
                                                    value="{{ $application->application_date }}" disabled>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <label for="" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control"
                                                value="{{ $application?->signOffs?->expiry_date }}" disabled>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close
                                    View</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <script src="https://unpkg.com/imask"></script>
            <script>
                const telephone = document.querySelector('input[name=telephone]');
                const fax_no = document.querySelector('input[name=fax_no]');
                const maskOptions2 = {
                    mask: '+1(000)000-0000'
                }

                const mask1 = IMask(telephone, maskOptions2);
                const mask2 = IMask(fax_no, maskOptions2);

                function changePage(button) {
                    if (document.getElementById('permits_info').style.display == "none") {
                        if (document.querySelector('input[name=name]').value == "" || document.querySelector('input[name=address]')
                            .value == "" || document.querySelector('input[name=telephone]').value == "" || document.querySelector(
                                'input[name=contact_person]').value == "" || document.querySelector('input[name=no_of_employees]')
                            .value == "" || document.querySelector('input[name=proposed_date]').value == "" || document
                            .querySelector('input[name=proposed_time]').value == "" || document.querySelector(
                                'input[name=application_date]').value == "") {
                            swal.fire({
                                title: "Ensure all required fields are entered",
                                text: "Use astericks as guide",
                                icon: 'error'
                            })
                            return;
                        }
                        document.getElementById('establishment_info').style.display = "none";
                        document.getElementById('permits_info').style.width = "100%";
                        document.getElementById('permits_info').style.display = "";
                        button.innerHTML = "Previous Section of Renewal";
                        table.columns.adjust().draw();
                        if (document.querySelector('.progress-bar').style.width == '0%') {
                            document.querySelector('.progress-bar').style.width = '50%';
                            document.querySelector('.progress-bar').innerHTML = "50%";
                        }
                    } else {
                        document.getElementById('establishment_info').style.display = "";
                        document.getElementById('permits_info').style.display = "none";
                        button.innerHTML = "Next Section of Renewal";
                    }
                }

                function submittable(values) {
                    if (values == "") {
                        document.getElementById('false-submit').style.display = "none";
                    } else {
                        document.getElementById('false-submit').style.display = "";
                    }
                }

                document.getElementById('false-submit').addEventListener('click', function() {
                    confirmationRenewal();
                })
            </script>
        </div>
        @include('partials.messages.establishment_renewal_message')
        @include('partials.messages.loading_message')
    </div>
@endsection
