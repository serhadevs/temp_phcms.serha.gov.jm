@extends('partials.layouts.layout')

@section('title', 'Administrative Dashboard')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
         <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="mb-3">
                    <h3 class="fw-bold fs-4 mb-3">Administrative Dashboard</h3>
                   <div class="row">
                        <div class="col-12 col-md-4 ">
                            <div class="card shadow">
                                <div class="card-header">
                                    <h5 class="fw-bold">
                                        STMP Settings
                                     </h5>
                                </div>
                                <div class="card-body py-4">
                                     <div class="mb-0">
                                        <span>
                                            <ul class="list-group">
                                                <li class="list-group-item"><span class="fw-bold">Host:</span> {{ $stmp->host }}</li>
                                                <li class="list-group-item"><span class="fw-bold">Port:</span> {{ $stmp->port }}</li>
                                                <li class="list-group-item"><span class="fw-bold">Username:</span> {{ $stmp->username }}</li>
                                                <li class="list-group-item"><span class="fw-bold">Password:</span> ****************</li>
                                                <li class="list-group-item"><span class="fw-bold">Encryption:</span> {{ $stmp->encryption }}</li>
                                                <li class="list-group-item"><span class="fw-bold">From Address:</span> {{ $stmp->from_address }}</li>
                                              </ul>
                                        </span>
                                    </div>
                                   
                                    
                                </div>
                                <div class="card-footer">
                                    <span><a href="{{ route('admin.create.stmp') }}" class="btn btn-secondary btn-sm">Change</a>
                                    </span>
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

                                <script src="sweetalert2.all.min.js"></script>

                                <script>
                                    @if ($message = Session::get('success'))
                                        Swal.fire({
                                            title: "Success!",
                                            text: "{{ $message }}",
                                            icon: "success"
                                        });
                                    @endif
                                
                                    @if ($message = Session::get('error'))
                                        Swal.fire({
                                            title: "Error!",
                                            text: "{{ $message }}",
                                            icon: "error"
                                        });
                                    @endif
                                </script>
                                

                            @endsection
