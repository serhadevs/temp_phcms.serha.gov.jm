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
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <p class="text-success"><strong>{{ $message }}</strong></p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <p class="text-danger font-weight-bold">{{ $message }}</p>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            <div class="card shadow my-2">
                <div class="card-header">Select the module you would like to search</div>
                <div class="card-body">
                    <form method="POST" id="permit_form" action="/advance-search/show">
                        @csrf
                        @method('POST')
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">Module</label>
                            <select class="form-select" id="module" name = "module">
                                <option value="10">Select Module </option>
                                @if (!in_array(auth()->user()->role_id, [4, 9]))
                                    <option value="1">Food Handlers</option>
                                    <option value="2">Onsite</option>
                                    <option value="3">Test Results</option>
                                    <option value="4">Health Interview</option>
                                @endif
                                @if (in_array(auth()->user()->role_id, [1, 4, 5, 8, 9]))
                                    <option value=8>Payments</option>
                                @endif
                            </select>
                        </div>

                        <div class="card my-3" id="foodhandlers1">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="First name">Firstname</label>
                                        <input type="text" class="form-control" name = "firstname"
                                            placeholder="First name" aria-label="First name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Last name">LastName</label>
                                        <input type="text" class="form-control" name = "lastname" placeholder="Last name"
                                            aria-label="Last name">
                                    </div>
                                </div>

                                <div class="row g-3 my-2">
                                    <div class="col-md-6">
                                        <label for="application">Application Number</label>
                                        <input type="text" class="form-control" placeholder="Application Number"
                                            aria-label="Application Number">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="establishment">Establishment Name</label>
                                        {{-- <input type="text" class="form-control" placeholder="Establishment Name" aria-label="Establishment Name"> --}}
                                        <select class="form-select" aria-label="Default select example"
                                            name="establishment_name">
                                            <option selected disabled>Select an Establishment</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card my-3" id="foodhandlers2">
                            <div class="card-body">

                                <div class="row g-3 my-2">
                                    <div class="col-md-6">
                                        <label for="Application Number">Application Number</label>
                                        <input type="text" class="form-control" placeholder="Application Number"
                                            aria-label="Application Number">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Food Establishment Name">Food Establishment Name</label>
                                        <input type="text" class="form-control" placeholder="Food Establishment Name"
                                            aria-label="Food Establishment Name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="establishment">Food Establishment Name</label>
                                        <select class="form-select" aria-label="Default select example">
                                            <option selected disabled>Select an Establishment</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card my-3" id="onsite">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="Application Number">Application Number ONSITE</label>
                                        <input type="text" class="form-control" placeholder="Application Number"
                                            aria-label="Application Number" name = "onsite_id">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="establishment">Establishment Name</label>
                                        <input type="text" class="form-control" placeholder="Establishment Name" aria-label="Establishment Name" name ="establishment_no">
                                        {{-- <select class="form-select" aria-label="Default select example">
                                            <option selected disabled>Select an Establishment</option>
                                            <option value="1">One</option>
                                            <option value="2">Two</option>
                                            <option value="3">Three</option>
                                        </select> --}}
                                    </div>
                                </div>
                            </div>

                        </div>
{{-- 
                        <div class="card my-3" id="testresults">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="First name">Firstname</label>
                                        <input type="text" class="form-control" name = "firstname"
                                            placeholder="First name" aria-label="First name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Last name">LastName</label>
                                        <input type="text" class="form-control" name = "lastname"
                                            placeholder="Last name" aria-label="Last name">
                                    </div>
                                </div>

                                <div class="row g-3 my-2">
                                    <div class="col-md-6">
                                        <label for="application">Application Number</label>
                                        <input type="text" class="form-control" placeholder="Application Number"
                                            aria-label="Application Number" name="app_id">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="establishment">Establishment Name</label>
                                        <select class="form-select" aria-label="Establishment Name" name = "test_type"
                                            id="test_type">
                                            <option value="1">Food Handlers</option>
                                            <option value="2">Food Establishments</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card my-3" id="health_interviews">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="First name">Firstname</label>
                                        <input type="text" class="form-control" name = "firstname"
                                            placeholder="First name" aria-label="First name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Last name">LastName</label>
                                        <input type="text" class="form-control" name = "lastname"
                                            placeholder="Last name" aria-label="Last name">
                                    </div>
                                </div>

                                <div class="row g-3 my-2">
                                    <div class="col-md-6">
                                        <label for="application">Application Number</label>
                                        <input type="text" class="form-control" placeholder="Application Number"
                                            aria-label="Application Number" name="interview_app_it">
                                    </div>
                                    <div class="col-md-6">
                                        <select class="form-select" aria-label="Default select example"
                                            name="interview_type">
                                            <option value="1">Food Handlers</option>
                                            <option value="2">Barbershop & Hair Salon</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card my-3" id="payments">
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="Application Number">Application #</label>
                                        <input type="text" class="form-control" placeholder="Application Number"
                                            aria-label="Application Number" pattern="[0-9]*$">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="Reciept Number">Receipt</label>
                                        <input type="text" class="form-control" placeholder="Reciept Number"
                                            aria-label="Reciept" pattern="[0-9]*$">
                                    </div>
                                </div>
                            </div>

                        </div> --}}

                        <button type="submit" class="btn btn-primary">Search</button>

                    </form>

                </div>
            </div>
        </div>
        <script>
            $("#foodhandlers1").hide();
            $("#foodhandlers2").hide();
            $("#onsite").hide();
            $("#testresults").hide();
            $("#health_interviews").hide();
            $("#payments").hide();

            $("#module").on('change', function() {
                let module_id = $(this).val();

                if (module_id == 10) {
                    $("#foodhandlers1").hide();
                    $("#foodhandlers2").hide();
                    $("#onsite").hide();
                    $("#testresults").hide();
                    $("#health_interviews").hide();
                    $("#payments").hide();
                } else if (module_id == 1) {
                    $("#foodhandlers1").show();
                    $("#onsite").hide();
                    $("#testresults").hide();
                    $("#health_interviews").hide();
                    $("#payments").hide();

                } else if (module_id == 2) {
                    $("#foodhandlers1").hide();
                    $("#onsite").show();
                    $("#testresults").hide();
                    $("#health_interviews").hide();
                    $("#payments").hide();

                } else if (module_id == 3) {
                    $("#foodhandlers1").hide();
                    $("#onsite").hide();
                    $("#testresults").show();
                    $("#health_interviews").hide();
                    $("#payments").hide();

                } else if (module_id == 8) {
                    $("#foodhandlers1").hide();
                    $("#onsite").hide();
                    $("#testresults").hide();
                    $("#health_interviews").hide();
                    $("#payments").show();
                }
            });

            $("#test_type").on('change', function() {
                let type_id = $(this).val();
                if (type_id == 2) {
                    $("#foodhandlers2").show();
                    $("#testresults").hide();
                    // $("#food_establishment_list").show();

                } else {
                    $("#foodhandlers2").show();
                    $("#testresults").hide();
                    // $("#food_establishment_list").hide();
                }
            });



            const hamBurger = document.querySelector(".toggle-btn");
            hamBurger.addEventListener("click", function() {
                document.querySelector("#sidebar").classList.toggle("expand");
            });
        </script>
    </div>
@endsection
