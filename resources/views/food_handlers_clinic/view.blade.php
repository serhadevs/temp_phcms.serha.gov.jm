@extends('partials.layouts.layout')

@section('title', 'View Food Handlers Clinic')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">
                        Application for {{ $application->name }}
                    </h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('food-handlers-clinic.update', ['id' => $application->id]) }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="">
                            <label for="" class="form-label">Application ID</label>
                            <input type="text" name="id" class="form-control" value="{{ $application->id }}"
                                readonly>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Establishment Clinic Name</label>
                            <input type="text" class="form-control" name="name" id="name" disabled
                                value="{{ old('name') ? old('name') : $application->name }}">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Establishment Address</label>
                            <input type="text" class="form-control" name="address" id="address"
                                value="{{ old('address') ? old('address') : $application->address }}" disabled>
                            @error('address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Telephone</label>
                                <input type="text" class="form-control" name="telephone" id="telephone" disabled
                                    value="{{ old('telephone') ? old('telephone') : $application->telephone }}">
                                @error('telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Fax Number</label>
                                <input type="text" class="form-control" name="fax_no" id="fax_no" disabled
                                    value="{{ old('fax_no') ? old('fax_no') : $application->fax_no }}">
                                @error('fax_no')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" id="contact_person" disabled
                                value="{{ old('contact_person') ? old('contact_person') : $application->contact_person }}">
                            @error('contact_person')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">No of Employees</label>
                                <input type="text" name="no_of_employees" class="form-control" id="no_of_employees"
                                    readonly
                                    value="{{ old('no_of_employees') ? old('no_of_employees') : $application->no_of_employees }}">
                                @error('no_of_employees')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            @if (!empty($application->payment->first()))
                                <div class="col col-md-2" style="display:flex">
                                    <button class="btn btn-success w-100" style="align-self:flex-end" type="button"
                                        onclick="updateEmployeeNumber({{ json_encode($application->id) }})">
                                        <i class="bi bi-pencil-square"></i>
                                        Request Change
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Proposed Date</label>
                                <input type="date" class="form-control" id="proposed_date" name="proposed_date" disabled
                                    value="{{ old('proposed_date') ? old('proposed_date') : $application->proposed_date }}">
                                @error('proposed_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Proposed Time</label>
                                <input type="text" class="form-control" id="proposed_time" name="proposed_time" disabled
                                    value="{{ old('proposed_time') ? old('proposed_time') : $application->proposed_time }}">
                                @error('proposed_time')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Number of Employee Applications added</label>
                                <input type="text" class="form-control" disabled
                                    value="{{ $application->permits_count }}">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Number of Employees Signed Off</label>
                                <input type="text" class="form-control" disabled
                                    value="{{ $applications_signed_off }}">
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Application Date</label>
                            <input type="date" class="form-control" id="application_date" name="application_date"
                                readonly
                                value="{{ old('application_date') ? old('application_date') : $application->application_date }}">
                            @error('application_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3" style="display:none" id="edit_reason_div">
                            <label for="" class="form-label">
                                <span class="fw-bold text-danger">*</span>
                                Reason for edit
                            </label>
                            <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="text" class="form-control mt-3" value="{{ isset($edit_mode) ? '1' : '' }}"
                            id="edit_mode_status" hidden>
                        <button id="update-button" type="button" class="btn btn-primary mt-4" style="display:none"
                            onclick="showLoading(this)">Update
                            information</button>

                        @if (!is_null($waiver_check) && $waiver_check->count() > 0)
                            <button class="btn btn-primary mt-3" type="button" data-bs-toggle="modal"
                                data-bs-target="#exampleModal"
                                onclick="populateModal({{ json_encode($application->toArray()) }}, {{ json_encode($waiver_check) }})"
                                data-establishment-id="{{ $application->id }}"
                                data-establishment-name="{{ $application->name }}">
                                Request Waiver
                            </button>
                        @endif
                        <button id="enable-editting" class="btn btn-warning mt-3" type="button"
                            onclick="enableEditing()">
                            Edit Application
                        </button>
                    </form>
                </div>
                <div class="card mx-3 mb-3">
                    <div class="card-header">
                        <h4 class="text-muted">
                            Employee Applications Added
                        </h4>
                    </div>
                    <div class="card-body">
                        @include('partials.tables.food_clinic_employees')
                    </div>
                </div>
                <div class="card mx-3">
                    <div class="card-header">
                        <h4 class="text-muted">Edit Transactions</h2>
                    </div>
                    <div class="card-body">
                        @include('partials.tables.edit_transactions_table')
                    </div>
                </div>
            </div>
        </div>
        <script>
            function enableEditing() {
                document.getElementById('update-button').style.display = "";
                document.getElementById('name').removeAttribute('disabled');
                document.getElementById('address').removeAttribute('disabled');
                document.getElementById('telephone').removeAttribute('disabled');
                document.getElementById('fax_no').removeAttribute('disabled');
                document.getElementById('contact_person').removeAttribute('disabled');
                document.getElementById('proposed_date').removeAttribute('disabled');
                document.getElementById('proposed_time').removeAttribute('disabled');
                document.getElementById('edit_reason_div').style.display = '';
                document.getElementById('enable-editting').style.display = 'none';
                if ({{ json_encode(empty($application->payment->first())) }}) {
                    document.querySelector('input[name=no_of_employees]').removeAttribute('readonly');
                }
            }
            window.onload = () => {
                if (document.getElementById('edit_mode_status').value == '1') {
                    enableEditing();
                }
            }
        </script>
        <script src="https://unpkg.com/imask"></script>
        <script>
            const telephone = document.getElementById('telephone');
            const fax_no = document.getElementById('fax_no');

            const maskOptions2 = {
                mask: '+1(000)000-0000'
            }

            const mask1 = IMask(telephone, maskOptions2);
            const mask2 = IMask(fax_no, maskOptions2);
        </script>
        <script>
            function updateEmployeeNumber(clinic_id) {
                swal.fire({
                    title: "Add Employee for Food Handlers Onsite Application",
                    text: "How many employees do you want to add.",
                    icon: 'question',
                    input: 'number',
                    inputAttributes: {
                        required: true,
                        min: 1
                    },
                    showCancelButton: true,
                    showConfirmButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        swal.fire({
                            title: "What is your reason for requesting this edit?",
                            text: 'This reason will be recorded',
                            icon: 'question',
                            input: 'textarea',
                            inputAttributes: {
                                required: true
                            },
                            showCancelButton: true,
                            showConfirmButton: true
                        }).then((result2) => {
                            if (result2.isConfirmed) {
                                swal.fire({
                                    title: 'Are you sure you want to request this edit?',
                                    icon: 'warning',
                                    showConfirmButton: true,
                                    showCancelButton: true
                                }).then((result3) => {
                                    if (result3.isConfirmed) {
                                        $.post({!! json_encode(url('/food-handlers-clinics/request/employees')) !!} + "/" + clinic_id, {
                                            _method: "POST",
                                            data: {
                                                request_amt: result.value,
                                                edit_reason: result2.value
                                            },
                                            _token: "{{ csrf_token() }}"
                                        }).then((data) => {
                                            if (data == 'success') {
                                                swal.fire({
                                                    title: "Done",
                                                    icon: 'success',
                                                    text: "Request for additional employees for this establishment clinic has been sent",
                                                }).then((esc) => {
                                                    if (esc) {
                                                        location.reload();
                                                    }
                                                })
                                            } else {
                                                swal.fire({
                                                    icon: 'error',
                                                    title: 'error',
                                                    text: data
                                                })
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
        @include('partials.messages.loading_message')

        @include('food_handlers_clinic.partials.waiverRequestModal')

        <script>
            let app_id = null;
            let establishment_id = null;
            let waiver_application_id = null;

            function populateModal(application, waiver_check) {
                // Set modal title
                const modalTitle = document.getElementById('modalTitle');
                modalTitle.textContent = `Request Waiver for ${application.name}`;

                // Store IDs for submission
                app_id = application.id;
                establishment_id = application.id;
                console.log(waiver_check);
                waiver_application_id = waiver_check

                console.log("Application ID:", app_id);
                console.log("Establishment ID:", establishment_id);
                console.log("Waiver Application ID:", waiver_application_id[0].id);

                // Optionally, prefill waiver amount
                const amountInput = document.getElementById('waiverAmountInput');
                if (waiver_check?.amount) {
                    amountInput.value = waiver_check.amount;
                } else {
                    amountInput.value = '';
                }
            }

            function submitWaiver() {
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = tokenMeta ? tokenMeta.content : '{{ csrf_token() }}'; // fallback
                const amount = document.getElementById('waiverAmountInput').value;


                if (!amount || isNaN(amount)) {
                    Swal.fire('Error!', 'Please enter a valid waiver amount.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'Submitting...',
                    text: 'Please wait while we process your request.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('/waivers/store', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            waiver_establishment_id: waiver_application_id[0].id,
                            application_id: app_id,
                            waiver_amount: amount
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        if (data.status === 'success') {
                            Swal.fire('Success!', data.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Error!', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error(error);
                        Swal.close();
                        Swal.fire('Error!', 'Something went wrong.', 'error');
                    });
            }
        </script>




    </div>

@endsection
