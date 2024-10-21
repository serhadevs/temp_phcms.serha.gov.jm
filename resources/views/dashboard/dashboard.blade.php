@extends('partials.layouts.layout')

@section('title', 'Dashboard')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="card shadow">
                    <div>
                        <div class="card-header">
                            <h3 class="fw-bold fs-4">Dashboard</h3>
                        </div>
            
                        <h4 class="fs-4 py-3 px-3">Welcome {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}</h4>
            
                        <div class="card-body py-3">
                            @if (in_array(auth()->user()->role_id, [1,3,10]))
                            <div class="row">
                                <!-- Food Permit Applications -->
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="card text-bg-light h-100">
                                        <div class="card-header mb-2 fw-bold">
                                            Food Permit Applications
                                        </div>
                                        <div class="card-body py-4">
                                            <div class="mb-0">
                                                <span class="badge text-success me-2">
                                                    {{ $permitApplicationCount }}
                                                </span>
                                                <span class="fw-bold">Since {{ $month }} {{ $year }}</span>
                                            </div>
                                            <div class="mb-0">
                                                <span class="badge text-success me-2">
                                                    {{ $permitApplicationCountYTD }}
                                                </span>
                                                <span class="fw-bold">Year to Date from January to {{ $month }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                                <!-- Food Establishment Applications -->
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="card text-bg-light h-100">
                                        <div class="card-header mb-2 fw-bold">
                                            Food Establishment Applications
                                        </div>
                                        <div class="card-body py-4">
                                            <div class="mb-0">
                                                <span class="badge text-success me-2">
                                                    {{ $foodestApplicationCount }}
                                                </span>
                                                <span class="fw-bold">Since {{ $month }} {{ $year }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
            
                                <!-- Barber/Cosmetology Applications -->
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="card text-bg-light h-100">
                                        <div class="card-header mb-2 fw-bold">
                                            Barber/Cosmetology
                                        </div>
                                        <div class="card-body py-4">
                                            <div class="mb-0">
                                                <span class="badge text-success me-2">
                                                    {{ $barbercosmApplicationCount }}
                                                </span>
                                                <span class="fw-bold">Since {{ $month }} {{ $year }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
            
                            @if (in_array(auth()->user()->role_id, [4]))
                            <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body py-4">
                                            <h5 class="mb-2 fw-bold">Payments</h5>
                                            <div class="mb-0">
                                                <span class="badge text-success me-2">
                                                    {{ $paymentCount }}
                                                </span>
                                                <span class="fw-bold">Since {{ $month }} {{ $year }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
    </div>

    </main>

    </div>

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
