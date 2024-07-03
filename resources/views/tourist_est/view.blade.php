@extends('partials.layouts.layout')

@section('title', 'View Torist Establishment')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">Toursist Establishment {{ $application->establishment_name }}</h2>
                </div>
                <div class="card-body">
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
                            <label for="" class="form-label">Payment Status</label>
                            <input type="text" disabled class="form-control"
                                value="{{ empty($application->payment) ? 'NOT PAID' : 'PAID' }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Sign Off Status</label>
                            <input type="text" class="form-control"
                                value="{{ $application->sign_off_status == '1' ? 'APPROVED' : 'NOT YET APPROVED' }}"
                                disabled>
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Entered By</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $application->user?->firstname . ' ' . $application->user?->lastname }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Application Date</label>
                            <input type="date" class="form-control" name="application_date"
                                value="{{ old('application_date') ? old('application_date') : $application->application_date }}"
                                disabled>
                        </div>
                    </div>
                    <form action="{{ route('tourist-establishments.update', ['id' => $application->id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input class="form-control" type="hidden" value="{{ $application->id }}" name="app_id">
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Establishment Name</label>
                                <input type="text" class="form-control" name="establishment_name"
                                    value="{{ old('establishment_name') ? old('establishment_name') : $application->establishment_name }}"
                                    disabled id="establishment_name">
                                @error('establishment_name')
                                    <p class="text-danger error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Establishment Address</label>
                                <input type="text" class="form-control" name="establishment_address"
                                    value="{{ old('establishment_address') ? old('establishment_address') : $application->establishment_address }}"
                                    disabled id="establishment_address">
                                @error('establishment_address')
                                    <p class="text-danger error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col"><label for="" class="form-label">Bed Capacity</label>
                                <input type="text" class="form-control" name="bed_capacity"
                                    value="{{ old('bed_capacity') ? old('bed_capacity') : $application->bed_capacity }}"
                                    disabled id="bed_capacity">
                                @error('bed_capacity')
                                    <p class="text-danger error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Is a eating establishment?</label>
                                <select name="is_eating_establishment" id="is_eating_establishment" disabled
                                    class="form-select">
                                    <option value="1"
                                        {{ old('is_eating_establishment') ? (old('is_eating_establishment') == '1' ? 'selected' : '') : ($application->is_eating_establishment == '1' ? 'selected' : '') }}>
                                        Yes</option>
                                    <option value="0"
                                        {{ old('is_eating_establishment') ? (old('is_eating_establishment') == '0' ? 'selected' : '') : ($application->is_eating_establishment == '0' ? 'selected' : '') }}>
                                        No</option>
                                </select>
                                @error('is_eating_establishment')
                                    <p class="text-danger error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Establishment State</label>
                                <select name="establishment_state" id="establishment_state" class="form-select" disabled>
                                    <option value="new"
                                        {{ old('establishment_state') ? (old('establishment_state') == 'new' ? 'selected' : '') : ($application->establishment_state == 'new' ? 'selected' : '') }}>
                                        New</option>
                                    <option value="now being operated"
                                        {{ old('establishment_state') ? (old('establishment_state') == 'now being operated' ? 'selected' : '') : ($application->establishment_state == 'now being operated' ? 'selected' : '') }}>
                                        Now Being Operated</option>
                                </select>
                                @error('establishment_state')
                                    <p class="text-danger error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label" id="">Description of
                                Establishment and Seating
                                Capacity</label>
                            <textarea name="eating_establishment_description" id="eating_establishment_description" class="form-control"
                                disabled>{{ old('eating_establishment_description') ? old('eating_establishment_description') : $application->eating_establishment_description }}</textarea>
                            @error('eating_establishment_description')
                                <p class="text-danger error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Authorized Officer First Name</label>
                                <input type="text" class="form-control" name="officer_firstname"
                                    value="{{ old('officer_firstname') ? old('officer_firstname') : $application->officer_firstname }}"
                                    disabled id="officer_firstname">
                                @error('officer_firstname')
                                    <p class="text-danger error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Authorized Officer Last Name</label>
                                <input type="text" class="form-control" name="officer_lastname"
                                    value="{{ old('officer_lastname') ? old('officer_lastname') : $application->officer_lastname }}"
                                    disabled id="officer_lastname">
                                @error('officer_lastname')
                                    <p class="text-danger error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Authorized Officer Statement</label>
                            <textarea class="form-control" name="authorized_officer_statement" disabled id="authorized_officer_statement">{{ old('authorized_officer_statement') ? old('authorized_officer_statement') : $application->authorized_officer_statement }}</textarea>
                            @error('authorized_officer_statement')
                                <p class="text-danger error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Statement Date</label>
                                <input type="date" class="form-control" name="statement_date"
                                    value="{{ old('statement_date') ? old('statement_date') : $application->statement_date }}"
                                    disabled id="statement_date">
                                @error('statement_date')
                                    <p class="text-danger error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3" style="display:none" id="edit_reason_div">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Reason for edit
                            </label>
                            <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger error">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="button" class="btn btn-warning mt-3" onclick="enableEditing()"
                            id="btn_edit">Edit
                            Application</button>
                        <div id="update_div" style="display:none">
                            <button class="btn btn-primary mt-3" type="button" onclick="showLoading(this)">
                                <i class="bi bi-pencil-square"></i>
                                Update Application Information
                            </button>
                            <button class="btn btn-danger mt-3" onclick="disableEdit()" type="button">
                                <i class="bi bi-box-arrow-left"></i>
                                Cancel
                            </button>
                        </div>
                    </form>
                    <div class="card mt-4">
                        <div class="card-header">
                            <div class="row justify-content-between">
                                <div class="col">
                                    <h4 class="text-muted">Management Team</h4>
                                </div>
                                <div class="col-auto">
                                    <a class="btn-primary btn"
                                        href="/tourist-establishments/managers/create/{{ $application->id }}">Add Team
                                        Member</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @include('partials.tables.tourist_est_managers')
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col col-md-6">
                            <div class="card" style="height:100%">
                                <div class="card-header">
                                    <div class="row justify-content-between">
                                        <div class="col">
                                            <h4 class="text-muted">Services/Facilities of Establishment</h4>
                                        </div>
                                        <div class="col-auto">
                                            <button onclick="addService({{ json_encode($application->id) }})"
                                                class="btn btn-primary">Add New Service</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @include('partials.tables.tourist_est_services_table')
                                </div>
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="card" style="height:100%">
                                <div class="card-header">
                                    <h4 class="text-muted">Edit Transactions</h4>
                                </div>
                                <div class="card-body">
                                    @include('partials.tables.edit_transactions_table')
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button class="btn-danger btn" onclick="history.back()">
                        <i class="bi bi-box-arrow-left"></i>
                        Back to Dashboard
                    </button>
                </div>
            </div>

            <input type="hidden" id="edit_mode" value="{{ isset($edit_mode) ? '1' : '' }}">
        </div>
        <script>
            window.onload = () => {
                if (document.querySelectorAll('.error')[0]) {
                    enableEditing();
                }

                if (document.getElementById('edit_mode').value == '1') {
                    enableEditing();
                }
            }

            function enableEditing() {
                document.getElementById('establishment_name').removeAttribute('disabled');
                document.getElementById('establishment_address').removeAttribute('disabled');
                document.getElementById('bed_capacity').removeAttribute('disabled');
                document.getElementById('is_eating_establishment').removeAttribute('disabled');
                document.getElementById('establishment_state').removeAttribute('disabled');
                document.getElementById('eating_establishment_description').removeAttribute('disabled');
                document.getElementById('officer_firstname').removeAttribute('disabled');
                document.getElementById('officer_lastname').removeAttribute('disabled');
                document.getElementById('authorized_officer_statement').removeAttribute('disabled');
                document.getElementById('statement_date').removeAttribute('disabled');
                document.getElementById('edit_reason_div').style.display = "";
                document.getElementById('update_div').style.display = "";
                document.getElementById('btn_edit').style.display = "none";
            }

            function disableEdit() {
                document.getElementById('establishment_name').setAttribute('disabled', 'true');
                document.getElementById('establishment_address').setAttribute('disabled', 'true');
                document.getElementById('bed_capacity').setAttribute('disabled', 'true');
                document.getElementById('is_eating_establishment').setAttribute('disabled', 'true');
                document.getElementById('establishment_state').setAttribute('disabled', 'true');
                document.getElementById('eating_establishment_description').setAttribute('disabled', 'true');
                document.getElementById('officer_firstname').setAttribute('disabled', 'true');
                document.getElementById('officer_lastname').setAttribute('disabled', 'true');
                document.getElementById('authorized_officer_statement').setAttribute('disabled', 'true');
                document.getElementById('edit_reason_div').style.display = "none";
                document.getElementById('statement_date').setAttribute('disabled', 'true');
                document.getElementById('update_div').style.display = "none";
                document.getElementById('btn_edit').style.display = "";
            }

            function addService($tourist_est_id) {
                swal.fire({
                        title: "Add New Service to Tourist\nEstablishment.",
                        icon: "question",
                        input: "text",
                        inputAttributes: {
                            required: true
                        },
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonText: `Yes, I am sure!`,
                        cancelButtonText: `No, Cancel it!`
                    })
                    .then(result => {
                        if (result.isConfirmed) {
                            swal.fire({
                                title: 'What is the reason you are\n adding this service?',
                                text: 'Reason will be recorded.',
                                icon: 'question',
                                input: "textarea",
                                inputAttributes: {
                                    required: true
                                },
                                showConfirmButton: true,
                                showCancelButton: true,
                                confirmButtonText: "Add Service",
                                cancelButtonText: "Cancel"
                            }).then((result3) => {
                                if (result3.isConfirmed) {
                                    $.post({!! json_encode(url('/tourist-establishments/services/add')) !!}, {
                                        _method: "POST",
                                        data: {
                                            name: result.value,
                                            tourist_est_id: $tourist_est_id,
                                            edit_reason: result3.value
                                        },
                                        _token: "{{ csrf_token() }}"
                                    }).then(function(data) {
                                        if (data[0] == "success") {
                                            swal.fire(
                                                "Done!",
                                                data[1],
                                                "success").then(esc => {
                                                if (esc) {
                                                    location.reload();
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
    </div>
    @include('partials.messages.loading_message')
@endsection
