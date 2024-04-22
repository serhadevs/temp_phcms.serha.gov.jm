@extends('partials.layouts.layout')

@section('title', 'Dashboard')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
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

        <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <h3 class="fw-bold fs-4 mb-3">Dashboard</h3>
                    <h4 class="fs-4 mb-3">Welcome {{ auth()->user()->firstname }} {{ auth()->user()->lastname }} </h4>

                    {{-- <div class="row mb-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    Upcoming Appointments
                                </div>
                                <div class="card-body">
                                    <div class="list-group">
                                        @if ($appointments->isNotEmpty())
                                            @foreach ($appointments as $appointment)
                                                <a href="#" class="list-group-item list-group-item-action">
                                                    <p class="mb-1">{{ optional($appointment->applications)->firstname }}
                                                        {{ optional($appointment->applications)->lastname }}</p>
                                                   
                                                </a>
                                            @endforeach
                                        @else
                                            <p>No Appointments for today</p>
                                        @endif
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div> --}}

                    <div class="row">
                        <div class="col-12 col-md-4 ">
                            <div class="card">
                                <div class="card-body py-4">
                                    <h5 class="mb-2 fw-bold">
                                        Onsite Applications
                                    </h5>
                                    <p class="mb-2 fw-bold">


                                    </p>
                                    <div class="mb-0">
                                        <span class="badge text-success me-2">
                                            +9.0%
                                        </span>
                                        <span class=" fw-bold">
                                            Since Last Month
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="card">
                                <div class="card-body py-4">
                                    <h5 class="mb-2 fw-bold">
                                        Food Permit Applications
                                    </h5>
                                    <div class="mb-0">
                                        <span class="badge text-success me-2">
                                            +9.0%
                                        </span>
                                        <span class="fw-bold">
                                            Since Last Month
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-4 ">
                            <div class="card">
                                <div class="card-body py-4">
                                    <h5 class="mb-2 fw-bold">
                                        Establishment Applications
                                    </h5>
                                    <div class="mb-0">
                                        <span class="badge text-success me-2">
                                            +9.0%
                                        </span>
                                        <span class="fw-bold">
                                            Since Last Month
                                        </span>
                                    </div>


                                </div>
                                <script>
                                    const hamBurger = document.querySelector(".toggle-btn");

                                    hamBurger.addEventListener("click", function() {
                                        document.querySelector("#sidebar").classList.toggle("expand");
                                    });
                                </script>

                            @endsection
