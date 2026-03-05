@extends('partials.layouts.layout')

@section('title', 'Collected Cards')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.confirmmessage')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-header">
                    Pick up Card for {{ $applicant->firstname }} {{ $applicant->lastname }}
                </div>
                <div class="card-body">
                    <form class="row g-3" action="{{ route('collectedcards.store') }}" method="POST">
                        @csrf
                        <div class="col-12">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value = "1" name = "pick_up_id"
                                    id="selfPickUp" checked>
                                <label class="form-check-label" for="selfPick">
                                    Self Pick Up (By Applicant)
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value = "2" name = "pick_up_id"
                                    id="bearerPickUp">
                                <label class="form-check-label" for="authorizedPick">
                                    Authorized Pickup (Bearer)
                                </label>
                            </div>
                        </div>

                        @php
                            $fullName = $applicant->firstname . ' ' . $applicant->lastname;
                        @endphp

                        <input type="text" name="app_id" value="{{ $applicant->id }}" hidden>
                        <input type="text" name="collected_by" value="{{ $fullName }}" hidden>
                        <input type="text" name="application_type" id="application_type_id" value="1" hidden>
                        <div class="col-md-3">
                            <label for="inputEmail4" class="form-label">Occupation</label>
                            <input type="text" class="form-control" id="occupation" name = "occupation"
                                value="{{ $applicant->occupation }}" readonly>
                        </div>
                        <div class="col-md-3">
                            <label for="inputPassword4" class="form-label">Bearer's Firstname</label>
                            <input type="text"
                                class="form-control @error('bearer_firstname')
                                is-invalid
                            @enderror"
                                id="bearer_firstname" name="bearer_firstname" disabled>
                            @error('bearer_firstname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="inputtext4" class="form-label">Bearer's Lastname</label>
                            <input type="text" class="form-control @error('bearer_lastname')
                                is-invalid
                            @enderror " id="bearer_lastname" name = "bearer_lastname"
                                disabled>
                                 @error('bearer_lastname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="inputtext4" class="form-label">Bearer's Contact Number</label>
                            <input type="text" class="form-control" id="bearer_contact_number"
                                name = "bearer_contact_number" disabled>
                        </div>
                        <div class="col-md-4">
                            <label for="identification_type_main">ID Type</label>
                            <select name="identification_type_id" id="identification_type_main"
                                class="form-select @error('identification_type_id')
                                is-invalid
                            @enderror">
                                <option value="" selected disabled>Select the ID present</option>
                                @if (strtolower($applicant->occupation) === 'student')
                                    @foreach ($id_types as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                @else
                                    @foreach ($id_types->where('id', '!=', 4) as $item)
<option value="{{ $item->id }}">{{ $item->name }}</option>
@endforeach
                                @endif
                               
                            </select>
                             @error('identification_type_id')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="identification_number">ID Number</label>
                            <input type="text" name="identification_number" id="identification_number"
                                class="form-control @error('identification_number')
                                    is-invalid
                                @enderror">
                            @error('identification_number')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                        <div class="col-md-2" id="issueDateContainer">
                            <label for="issue_date" class="me-2 mb-0" style="width: 120px;" id="issue_date_label">Issue
                                Date</label>
                            <input type="date" id="issue_date" class="form-control @error('issue_date')
                                is-invalid
                            @enderror" name="issue_date">
                            @error('issue_date')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                        <div class="col-md-2" id="expiryDateContainer">
                            <label for="expiry_date" class="me-2 mb-0" style="width: 120px;" id="expiry_date_label">Expiry
                                Date</label>
                            <input type="date" id="expiry_date" class="form-control @error('expiry_date')
                                is-invalid
                            @enderror" name="expiry_date">
                            @error('expiry_date')
    <div class="invalid-feedback">{{ $message }}</div>
@enderror
                        </div>
                        {{-- <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="gridCheck" name="terms">
                                <label class="form-check-label" for="gridCheck">
                                    I agree
                                </label>
                            </div>
                        </div> --}}



                </div>
                <div class="card-footer">
                    <div class="col-12">
                        <a href="{{ route('permit.application.view', ['id' => $applicant->id]) }}" class="btn btn-danger">Back
                            to View Application</a>
                        <button type="submit" class="btn btn-success">Submit </button>
                    </div>
                </div>
                </form>
            </div>

        </div>

    </div>

    <script src="https://unpkg.com/imask"></script>
   <script>
       document.addEventListener('DOMContentLoaded', function() {
           const bearerCheckBox = document.getElementById('bearerPickUp');
           const selfPickUpBox = document.getElementById('selfPickUp');
           const bearerFirstName = document.getElementById('bearer_firstname');
           const bearerLastName = document.getElementById('bearer_lastname');
           const bearerContactNumber = document.getElementById('bearer_contact_number');
           const identificationNumber = document.getElementById('identification_number');
           const idType = document.getElementById('identification_type_main');

           const issueDateContainer = document.getElementById('issueDateContainer');
           const expiryDateContainer = document.getElementById('expiryDateContainer');

           // Function to handle checkbox state (radio-like behavior)
           function handlePickupType() {
               // Get the Student ID option (value 4)
               const studentIDOption = idType.querySelector('option[value="4"]');

               if (bearerCheckBox.checked) {
                   // Bearer pickup selected
                   bearerFirstName.disabled = false;
                   bearerLastName.disabled = false;
                   bearerFirstName.required = true;
                   bearerLastName.required = true;
                   bearerContactNumber.disabled = false;
                   bearerContactNumber.required = true;

                   // Disable Student ID option for bearer pickup
                   if (studentIDOption) {
                       studentIDOption.disabled = true;
                   }

                   // If Student ID was selected, reset the dropdown
                   if (idType.value == "4") {
                       idType.value = "";
                   }

                   // Show date fields for bearer pickup (unless Student ID)
                   if (idType.value != "4") {
                       issueDateContainer.style.display = 'block';
                       expiryDateContainer.style.display = 'block';
                   }

                   // Uncheck self pickup
                   selfPickUpBox.checked = false;

               } else if (selfPickUpBox.checked) {
                   // Self pickup selected
                   bearerFirstName.disabled = true;
                   bearerLastName.disabled = true;
                   bearerFirstName.required = false;
                   bearerLastName.required = false;
                   bearerContactNumber.disabled = true;
                   bearerContactNumber.required = false;
                   bearerFirstName.value = '';
                   bearerLastName.value = '';
                   bearerContactNumber.value = '';

                   // Enable Student ID option for self pickup
                   if (studentIDOption) {
                       studentIDOption.disabled = false;
                   }

                   // Hide date fields for self pickup
                   issueDateContainer.style.display = 'block';
                   expiryDateContainer.style.display = 'block';

                   // Uncheck bearer pickup
                   bearerCheckBox.checked = false;

               } else {
                   // Nothing selected - disable bearer fields, enable Student ID
                   bearerFirstName.disabled = true;
                   bearerLastName.disabled = true;
                   bearerFirstName.required = false;
                   bearerLastName.required = false;
                   bearerContactNumber.disabled = false;

                   // Enable Student ID option when nothing is selected
                   if (studentIDOption) {
                       studentIDOption.disabled = false;
                   }

                   issueDateContainer.style.display = 'none';
                   expiryDateContainer.style.display = 'none';
               }
           }

           // Function to handle ID type changes
           function handleIDTypeChange() {
               const selectedValue = idType.value;

               // Hide date fields for Student ID (value 4)
               if (selectedValue == "4") {
                   issueDateContainer.style.display = 'none';
                   expiryDateContainer.style.display = 'none';
               } else {
                   // Show date fields only if bearer pickup is checked
                   if (bearerCheckBox.checked) {
                       issueDateContainer.style.display = 'block';
                       expiryDateContainer.style.display = 'block';
                   }
               }

               // Apply input mask
               let maskOptions;
               if (selectedValue === "1") {
                   maskOptions = {
                       mask: '000-000-000'
                   };
                   identificationNumber.placeholder = '___-___-___';
               } else {
                   maskOptions = {
                       mask: '0000000'
                   };
                   identificationNumber.placeholder = '_______';
               }

               if (mask) mask.destroy();
               if (typeof IMask !== 'undefined') {
                   mask = IMask(identificationNumber, maskOptions);
               }
           }

           maskOptions = {
               mask: '+1(000)-000-0000'
           };

           mask2 = IMask(bearerContactNumber, maskOptions);

           // Initialize on page load
           handlePickupType();

           // Add event listeners
           bearerCheckBox.addEventListener('change', handlePickupType);
           selfPickUpBox.addEventListener('change', handlePickupType);
           idType.addEventListener('change', handleIDTypeChange);

           let mask; // IMask instance

           bearerFirstName.addEventListener('input', function() {
               this.value = this.value.toUpperCase();
           });
           bearerLastName.addEventListener('input', function() {
               this.value = this.value.toUpperCase();
           });
       });
   </script>
@endsection
