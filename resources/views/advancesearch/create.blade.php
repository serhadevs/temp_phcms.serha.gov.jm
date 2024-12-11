@extends('partials.layouts.layout')

@section('title', 'Advanced Search')

@php

@endphp
@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.messages')
            <div class="card shadow my-2">
                <h4 class="card-header text-muted">Advanced Search</h4>
                <div class="card-body">
                    <form method="POST" id="permit_form" action="{{ route('advance.search.show') }}">
                        @csrf
                        @method('POST')
                        <div class="input-group">
                            <label class="input-group-text" for="inputGroupSelect01">Module</label>
                            <select class="form-select" id="module" name="module">
                                <option selected disabled>Select Module </option>
                                @if (!in_array(auth()->user()->role_id, [4, 9]))
                                    <option value="1">Food Handlers</option>
                                    <option value="2">Onsite</option>
                                    <option value="6">Food Establishment</option>
                                    <option value="3">Test Results</option>
                                    <option value="4">Health Interview</option>
                                @endif
                                @if (in_array(auth()->user()->role_id, [1, 4, 5, 8, 9]))
                                    <option value="5">Payments</option>
                                @endif
                            </select>
                        </div>
                        @error('module')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                        <div class="row g-3" id="name_div" style="display:none">
                            <div class="col-md-6" id="first_name_div">
                                <label for="First name" class="form-label">Firstname</label>
                                <input type="text" class="form-control" name = "firstname" placeholder="First name"
                                    aria-label="First name">
                            </div>
                            <div class="col-md-6" id="last_name_div">
                                <label for="Last name" class="form-label">LastName</label>
                                <input type="text" class="form-control" name="lastname" placeholder="Last name"
                                    aria-label="Last name">
                            </div>
                        </div>
                        <div class="row g-3 my-2">
                            <div class="col-md-6" id="app_no_div" style="display:none">
                                <label for="application" class="form-label">Application Number</label>
                                <input type="text" class="form-control" placeholder="Application Number"
                                    aria-label="Application Number" name="application_number">
                            </div>
                            <div class="col-md-6" id="est_clinic_name_div" style="display:none">
                                <label for="establishment" class="form-label">Establishment Clinic Name</label>
                                <input class="form-control" list="clinicOptions" id="dataList"
                                    placeholder="Type to search..." name="establishment_clinic_name">
                                <datalist id="clinicOptions">
                                    @foreach ($establishment_clinics as $est)
                                        <option value="{{ $est->name }}">{{ $est->name }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col-md-6" id="app_type_div" style="display:none">
                                <label for="app_type" class="form-label">Application Type</label>
                                <select class="form-select" aria-label="app_type" name="app_type" id="app_type">
                                    <option value="1">Food Handlers</option>
                                    <option value="2">Food Establishment</option>
                                    <option value="3">Barber & Hair Salon</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="receipt_div" style="display:none">
                                <label for="Receipt No" class="form-label">Receipt Number</label>
                                <input type="text" class="form-control" aria-label="Receipt No" name="receipt_no">
                            </div>
                            <div class="col col-md-6" id="food_est_name_div" style="display:none">
                            
                                <label for="exampleDataList" class="form-label">Food Establishment Name</label>
                                <input class="form-control" list="datalistOptions"
                                    placeholder="Type to search..." name ="food_est_name">
                                <datalist id="datalistOptions">
                                    @foreach ($food_establishments as $food_est)
                                        <option value="{{ $food_est->establishment_name }}">
                                            {{ $food_est->establishment_name }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="col col-md-6 col-sm-12 mt-3" id="operator_div" style="display:none">
                                <label for="" class="form-label">Operator Name</label>
                                <input type="text" class="form-control" name="operator_name"
                                    placeholder="Type to Search..." list="operatorsList">
                                <datalist id="operatorsList">
                                    @foreach ($operators as $operator)
                                        <option value="{{ $operator->name_of_operator }}">{{ $operator->name_of_operator }}
                                        </option>
                                    @endforeach
                                </datalist>
                            </div>

                            <div class="col col-md-6 col-sm-12 mt-3" id ="address" style="display: none">
                                <label for="" class="form-label">Establishment Address</label>
                                <input type = "text" class="form-control" list="establishmentAddress"
                                    placeholder="Type to search..." name ="address">
                                <datalist id="establishmentAddress">
                                    @foreach ($food_addresses as $food_est_address)
                                        <option value="{{ $food_est_address->establishment_address }}">
                                            {{ $food_est_address->establishment_address }}</option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                    <a  onclick = "history.back()" class="btn btn-danger">Back</a>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </div>
            </form>
        </div>
        <script>
            function hideAll() {
                document.getElementById('name_div').style.display = "none";
                document.getElementById('app_no_div').style.display = "none";
                document.getElementById('est_clinic_name_div').style.display = "none";
                document.getElementById('app_type_div').style.display = "none";
                document.getElementById('receipt_div').style.display = "none";
                document.getElementById('food_est_name_div').style.display = "none";
                document.getElementById('operator_div').style.display = "none";
            }

            document.getElementById('module').addEventListener('change', function() {
                hideAll();
                if (this.value == '1') {
                    document.getElementById('name_div').style.display = "";
                    document.getElementById('app_no_div').style.display = "";
                    document.getElementById('est_clinic_name_div').style.display = "";
                } else if (this.value == '2') {
                    document.getElementById('app_no_div').style.display = "";
                    document.getElementById('est_clinic_name_div').style.display = "";
                } else if (this.value == "3") {
                    document.getElementById('name_div').style.display = "";
                    document.getElementById('app_no_div').style.display = "";
                    document.getElementById('app_type_div').style.display = "";
                    document.querySelector('#app_type option:nth-child(3)').style.display = "none";
                    document.querySelector('#app_type option:nth-child(2)').style.display = "";
                    document.getElementById('address').style.display = "none"
                } else if (this.value == "4") {
                    document.getElementById('name_div').style.display = "";
                    document.getElementById('app_no_div').style.display = "";
                    document.getElementById('app_type_div').style.display = "";
                    document.querySelector('#app_type option:nth-child(3)').style.display = "";
                    document.querySelector('#app_type option:nth-child(2)').style.display = "none";

                } else if (this.value == '5') {
                    document.getElementById('app_no_div').style.display = "";
                    document.getElementById('receipt_div').style.display = "";

                } else if (this.value == '6') {
                    document.getElementById('food_est_name_div').style.display = "";
                    document.getElementById('app_no_div').style.display = "";
                    document.getElementById('operator_div').style.display = "block";
                    document.getElementById('address').style.display = ""
                }
            })

            document.getElementById('app_type').addEventListener('change', function() {
                if (this.value == "1") {
                    document.getElementById('name_div').style.display = "";
                    document.getElementById('app_no_div').style.display = "";
                    document.getElementById('app_type_div').style.display = "";
                    document.querySelector('#app_type option:nth-child(3)').style.display = "none";
                    document.querySelector('#app_type option:nth-child(2)').style.display = "";
                    document.getElementById('food_est_name_div').style.display = "none";
                    document.getElementById('address').style.display = "none"
                } else if (this.value == '2') {
                    document.getElementById('name_div').style.display = "none";
                    document.getElementById('food_est_name_div').style.display = "";
                    document.getElementById('address').style.display = "none"
                }
            })
        </script>
    </div>
@endsection
