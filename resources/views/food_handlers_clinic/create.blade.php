@extends('partials.layouts.layout')

@section('title', 'New Food Handlers CLinic')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header text-muted">
                    <h2>Create New Food Handler's Clinic</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('food-handlers-clinic.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mt-3">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="gridCheck">
                                    <label class="form-check-label" for="gridCheck">
                                        Does this establishment have a waiver applied?
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 mb-3" id="waiver_establishment">
                            <div class="row gt-3">
                                <div class="col-md-6">
                                    <label for="" class="form-label">Select Establishment (If Waiver
                                        Applied)</label>
                                    <select name="waiver_establishment_id" id="waiver_establishment_id" class="form-select">
                                        <option value="">-- Select Establishment --</option>
                                        @foreach ($waivers as $waiver)
                                            <option value="{{ $waiver->id }}"
                                                {{ old('waiver_establishment_id') == $waiver->id ? 'selected' : '' }}>
                                                {{ $waiver->establishment_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('waiver_establishment_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror


                                </div>

                                <div class="col-md-6">
                                    <label for="" class="form-label">Amount Being Waived</label>
                                    <div class="input-group mb-3">

                                        <span class="input-group-text">$</span>
                                        <input type="text" class="form-control @error('waiver_amount')
                                            is-invalid
                                        @enderror"
                                            aria-label="Amount (to the nearest dollar)" name="waiver_amount"
                                            value="{{ old('waiver_amount') }}">
                                        <span class="input-group-text">.00</span>
                                        @error('waiver_amount')
                                            <div class="is-invalid">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                        </div>


                        <div class="mt-3">
                            <label for="" class="form-label">Establishment Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}"
                                oninput="this.value = this.value.toUpperCase()" id="establishment_name">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="{{ old('address') }}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Telephone Number</label>
                                <input type="text" class="form-control" name="telephone" value="{{ old('telephone') }}"
                                    id="telephone">
                                @error('telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Fax Number</label>
                                <input type="text" name="fax_no" class="form-control" value="{{ old('fax_no') }}"
                                    id="fax_no">
                                @error('fax_no')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" name="contact_person"
                                value="{{ old('contact_person') }}" oninput="this.value = this.value.toUpperCase()">
                            @error('contact_person')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">No. of Emloyees</label>
                            <input type="text" class="form-control" name="no_of_employees"
                                value="{{ old('no_of_employees') }}">
                            @error('no_of_employees')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label for="" class="form-label">Proposed Exercise Date</label>
                                <input type="date" class="form-control" name="proposed_date"
                                    value="{{ old('proposed_date') }}">
                                @error('proposed_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="" class="form-label">Proposed Exercise Time</label>
                                <input type="time" class="form-control" name="proposed_time"
                                    value="{{ old('proposed_time') }}" oninput="this.value = this.value.toUpperCase()">
                                @error('proposed_time')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>


                        <div class="mt-3">
                            <label for="" class="form-label">Application Date</label>
                            <input type="date" class="form-control" name="application_date"
                                value="{{ old('application_date') }}" max="{{ date('Y-m-d') }}">
                            @error('application_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>




                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                    <button class="btn btn-primary" type="button" onclick="showLoading(this)">
                        Submit Application
                    </button>
                </div>
                </form>
            </div>
        </div>
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
            document.addEventListener('DOMContentLoaded', function() {
                const waiverEstablishmentDiv = document.getElementById('waiver_establishment');
                const waiverEstablishmentSelect = document.getElementById('waiver_establishment_id');
                const gridCheck = document.getElementById('gridCheck');
                const establishmentNameInput = document.getElementById('establishment_name');

                // Initially hide the waiver establishment div
                waiverEstablishmentDiv.style.display = 'none';

                gridCheck.addEventListener('change', function() {
                    if (this.checked) {
                        waiverEstablishmentDiv.style.display = 'block';
                    } else {
                        waiverEstablishmentDiv.style.display = 'none';
                        // Clear the selected value when hiding
                        document.getElementById('waiver_establishment_id').value = '';


                    }
                });

                waiverEstablishmentSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value) {
                        establishmentNameInput.value = selectedOption.text;
                        establishmentNameInput.readOnly = true;
                    } else {
                        establishmentNameInput.value = '';
                        establishmentNameInput.readOnly = false;
                    }
                });


            });
        </script>
        @include('partials.messages.loading_message')
    </div>
@endsection
