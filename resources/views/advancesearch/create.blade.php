@extends('partials.layouts.layout')

@section('title', 'Advanced Search')

@php

@endphp
@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <h2>Advanced Search</h2>
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <p class="text-success"><strong>{{ $message }}</strong></p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <p class="text-danger font-weight-bold">{{ $message }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card shadow my-2">
                <div class="card-header">Select the module you would like to search</div>
                <div class="card-body">
                    <form method="POST" id="permit_form" action="{{ route('advance.search.show') }}">
                        @csrf
                        @method('POST')
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">Module</label>
                            <select class="form-select" id="module" name="module">
                                <option selected disabled>Select Module </option>
                                @if (!in_array(auth()->user()->role_id, [4, 9]))
                                    <option value="1">Food Handlers</option>
                                    <option value="2">Onsite</option>
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
                                <label for="First name">Firstname</label>
                                <input type="text" class="form-control" name = "firstname" placeholder="First name"
                                    aria-label="First name">
                            </div>
                            <div class="col-md-6" id="last_name_div">
                                <label for="Last name">LastName</label>
                                <input type="text" class="form-control" name="lastname" placeholder="Last name"
                                    aria-label="Last name">
                            </div>
                        </div>
                        <div class="row g-3 my-2">
                            <div class="col-md-6" id="app_no_div" style="display:none">
                                <label for="application">Application Number</label>
                                <input type="text" class="form-control" placeholder="Application Number"
                                    aria-label="Application Number" name="application_number">
                            </div>
                            <div class="col-md-6" id="est_clinic_name_div" style="display:none">
                                <label for="establishment">Establishment Clinic Name</label>
                                <select class="form-select" aria-label="establishment" name="establishment_clinic_name">
                                    <option selected disabled>Select an Establishment</option>
                                    @foreach ($establishment_clinics as $est)
                                        <option value="{{ $est->name }}">{{ $est->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6" id="app_type_div" style="display:none">
                                <label for="app_type">Application Type</label>
                                <select class="form-select" aria-label="app_type" name="app_type" id="app_type">
                                    <option value="1">Food Handlers</option>
                                    <option value="2">Food Establishment</option>
                                    <option value="3">Barber & Hair Salon</option>
                                </select>
                            </div>
                            <div class="col-md-6" id="receipt_div" style="display:none">
                                <label for="Receipt No">Receipt Number</label>
                                <input type="text" class="form-control" aria-label="Receipt No" name="receipt_no">
                            </div>
                            <div class="col col-md-6" id="food_est_name_div" style="display:none">
                                <label for="">Food Establishment Name</label>
                                <select name="food_est_name" id="" class="form-select">
                                    <option disabled selected class="text-center">-------------Select a food
                                        establishment name-------------</option>
                                    @foreach ($food_establishments as $food_est)
                                        <option value="{{ $food_est->establishment_name }}">
                                            {{ $food_est->establishment_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Search</button>
                    </form>
                </div>
            </div>
        </div>
        <script>
            function hideAll() {
                document.getElementById('name_div').style.display = "none";
                document.getElementById('app_no_div').style.display = "none";
                document.getElementById('est_clinic_name_div').style.display = "none";
                document.getElementById('app_type_div').style.display = "none";
                document.getElementById('receipt_div').style.display = "none";
                document.getElementById('food_est_name_div').style.display = "none";
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
                } else if (this.value == "4") {
                    document.getElementById('name_div').style.display = "";
                    document.getElementById('app_no_div').style.display = "";
                    document.getElementById('app_type_div').style.display = "";
                    document.querySelector('#app_type option:nth-child(3)').style.display = "";
                    document.querySelector('#app_type option:nth-child(2)').style.display = "none";
                } else if (this.value == '5') {
                    document.getElementById('app_no_div').style.display = "";
                    document.getElementById('receipt_div').style.display = "";
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
                } else if (this.value == '2') {
                    document.getElementById('name_div').style.display = "none";
                    document.getElementById('food_est_name_div').style.display = "";
                }
            })
        </script>
    </div>
@endsection
