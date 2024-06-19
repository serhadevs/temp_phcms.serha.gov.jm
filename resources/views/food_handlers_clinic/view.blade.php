@extends('partials.layouts.layout')

@section('title', 'View Food Handlers Clinic')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        {{-- @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <p class="text-success"><strong>{{ $message }}</strong></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <p class="text-danger font-weight-bold"><strong>{{ $message }}</strong></p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif --}}
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
                        <div class="mt-3">
                            <label for="" class="form-label">No of Employees</label>
                            <input type="text" name="no_of_employees" class="form-control" id="no_of_employees" readonly
                                value="{{ old('no_of_employees') ? old('no_of_employees') : $application->no_of_employees }}">
                            @error('no_of_employees')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
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
                        <button id="enable-editting" class="btn btn-warning mt-3" type="button"
                            onclick="enableEditing()">
                            Edit Application
                        </button>
                    </form>
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
        @include('partials.messages.loading_message')
    </div>
@endsection
