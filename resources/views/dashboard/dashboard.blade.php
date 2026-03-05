@php
    use Carbon\Carbon;

    $user = Auth::user();
    $expiryDate = $user->password_changed_at ? Carbon::parse($user->password_changed_at)->addDays(90) : null;

    $daysRemaining = $expiryDate ? now()->diffInDays($expiryDate, false) : null;

    // Determine color based on time left
    if (is_null($daysRemaining)) {
        $color = 'text-secondary';
    } elseif ($daysRemaining <= 0) {
        $color = 'text-danger'; // expired
    } elseif ($daysRemaining <= 10) {
        $color = 'text-warning'; // less than 10 days left
    } else {
        $color = 'text-success'; // safe
    }
@endphp
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

                        <h4 class="fs-4 py-3 px-3">Welcome {{ auth()->user()->firstname }} {{ auth()->user()->lastname }}

                        </h4>
                        <p class="fs-4 py-1 px-3 {{ $color }}">
                            @if ($expiryDate)
                                @if ($daysRemaining <= 0)
                                    Your password expired on {{ $expiryDate->format('F j, Y') }}.
                                @else
                                    Your password will expire on {{ $expiryDate->format('F j, Y') }}
                                    ({{ $expiryDate->diffForHumans() }}).
                                @endif
                            @else
                                You haven't set a password change date yet.
                            @endif
                        </p>

                        <div class="card-body py-3">
                            @if (in_array(auth()->user()->role_id, [1, 3, 10]))
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
                                                    <span class="fw-bold">Since {{ $month }}
                                                        {{ $year }}</span>
                                                </div>
                                                <div class="mb-0">
                                                    <span class="badge text-success me-2">
                                                        {{ $permitApplicationCountYTD }}
                                                    </span>
                                                    <span class="fw-bold">Year to Date from January to
                                                        {{ $month }}</span>
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
                                                    <span class="fw-bold">Since {{ $month }}
                                                        {{ $year }}</span>
                                                </div>
                                                <div class="mb-0">
                                                    <span class="badge text-success me-2">
                                                        {{ $foodestApplicationCountYTD }}
                                                    </span>
                                                    <span class="fw-bold">Year to Date from January to
                                                        {{ $month }}</span>
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
                                                    <span class="fw-bold">Since {{ $month }}
                                                        {{ $year }}</span>
                                                </div>
                                                <div class="mb-0">
                                                    <span class="badge text-success me-2">
                                                        {{ $barbercosmApplicationCountYTD }}
                                                    </span>
                                                    <span class="fw-bold">Year to Date from January to
                                                        {{ $month }}</span>
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
                                                    <span class="fw-bold">Since {{ $month }}
                                                        {{ $year }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body py-3">
                            @if (in_array(auth()->user()->role_id, [1, 3, 10]))
                                {{-- <div class="row">
                                    <!-- Food Establishments that are expiring -->
                                    <div class="col mb-3">
                                        <div class="card text-bg-light h-100">
                                            <div
                                                class="card-header mb-2 fw-bold d-flex justify-content-between align-items-center">
                                                <span>Expiring Food Establishments</span>
                                                <select name="expiry_date" id="expiry_date" class="form-select w-auto"
                                                    style="cursor: pointer;">
                                                    <option value="0">Expiring Today</option>
                                                    <option value="30">Within 30 Days</option>
                                                    <option value="60">Within 60 Days</option>
                                                    <option value="90">Within 90 Days</option>
                                                </select>
                                            </div>

                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge text-danger">
                                                        <h6 class="mb-0" id="expiryCount" style="word-wrap: break-word;">
                                                        </h6>
                                                    </span>
                                                    <span>
                                                        <a href="" class="btn btn-primary " id="expiryBtn">View</a>
                                                    </span>
                                                </div>
                                            </div>


                                        </div>


                                    </div> 

                                  
                                </div> --}}


                                <div class="row">
                                    <!-- Food Establishments that are expiring -->
                                    <div class="col mb-3">
                                        <div class="card text-bg-light h-100">
                                            <div
                                                class="card-header mb-2 fw-bold d-flex justify-content-between align-items-center">
                                                <span>Expiring Food Establishments(that have not been renewed)</span>
                                                {{-- <select name="expiry_date" id="expiry_date" class="form-select w-auto"
                                                    style="cursor: pointer;">
                                                    <option value="0">Expiring Today</option>
                                                    <option value="30">Within 30 Days</option>
                                                    <option value="60">Within 60 Days</option>
                                                    <option value="90">Within 90 Days</option>
                                                </select> --}}
                                            </div>

                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <ol class="list-group w-100">

                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold">Food Establishments expiring within 30
                                                                    Days </div>

                                                            </div>
                                                            <a href="{{ route('food-establishments.expiry', ['days' => 30]) }}"
                                                                class="btn btn-primary">View</a>

                                                        </li>
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold">Food Establishments expiring within 60
                                                                    Days </div>

                                                            </div>
                                                            <a href="{{ route('food-establishments.expiry', ['days' => 60]) }}"
                                                                class="btn btn-primary">View</a>

                                                        </li>
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold">Food Establishments expiring within 90
                                                                    Days </div>

                                                            </div>
                                                            <a href="{{ route('food-establishments.expiry', ['days' => 90]) }}"
                                                                class="btn btn-primary">View</a>

                                                        </li>

                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-start">
                                                            <div class="ms-2 me-auto">
                                                                <div class="fw-bold">View all Food Establishments that have
                                                                    expired between a date</div>

                                                            </div>
                                                            <a href="{{ route('est.expired') }}"
                                                                class="btn btn-primary">View </a>

                                                        </li>
                                                    </ol>
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
                                                    <span class="fw-bold">Since {{ $month }}
                                                        {{ $year }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        {{-- <div class="card-body py-3">
                            @if (in_array(auth()->user()->role_id, [1, 3, 10]))
                                <div class="row">
                                    <!-- Food Permit Applications -->
                                    <div class="col mb-3">
                                        <div class="card text-bg-light h-100">
                                            <div
                                                class="card-header mb-2 fw-bold d-flex justify-content-between align-items-center">
                                                <span>Expiring Tourist Establishments</span>
                                                <select name="expiry_date" id="expiry_date" class="form-select w-auto"
                                                    style="cursor: pointer;">
                                                    <option value="0">Expiring Today</option>
                                                    <option value="30">Within 30 Days</option>
                                                    <option value="60">Within 60 Days</option>
                                                    <option value="90">Within 90 Days</option>
                                                </select>
                                            </div>

                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge text-danger">
                                                        <h6 class="mb-0" id="expiryCount" style="word-wrap: break-word;">
                                                        </h6>
                                                    </span>
                                                    <span>
                                                        <a href="" class="btn btn-primary " id="expiryBtn">View</a>
                                                    </span>
                                                </div>
                                            </div>


                                        </div>


                                    </div> 

                                  
                                </div>
                            @endif

                        </div> --}}
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

    <script src="{{ asset('js/jquery.min.js') }}"></script>

@endsection
