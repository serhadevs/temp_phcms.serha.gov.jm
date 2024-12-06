@extends('partials.layouts.layout')

@section('title', 'Sign Off Controller')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')

        <main class="content py-4">
            <div class="container-fluid">
                <div class="card shadow">
                    <h4 class="card-header text-muted">
                        Sign Off Applications
                    </h4>
                    <div class="card-body">
                        <div>
                            <div class="row mb-2">
                                @foreach ($application_type as $app_type)
                                    <div class="col-12 col-md-3 mb-3">
                                        <div class="card">
                                            <div class="card-body py-4">
                                                <a href="/sign-off/create/{{ $app_type->id }}"
                                                    class="text-decoration-none text-secondary">
                                                    <h5 class="mb-2 fw-bold">
                                                        {{ $app_type->name }}
                                                    </h5>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if (auth()->user()->role_id == 1)
                                    <div class="col-12 col-md-3 mb-3">
                                        <div class="card">
                                            <div class="card-body py-4">
                                                <a href="/sign-off/reversal/requests/index" class="text-decoration-none text-secondary">
                                                    <h5 class="mb-2 fw-bold">
                                                        Reverse Sign Off Requests
                                                    </h5>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                    </div>
                </div>

            </div>
        </main>
    </div>
    <script>
        const hamBurger = document.querySelector(".toggle-btn");

        hamBurger.addEventListener("click", function() {
            document.querySelector("#sidebar").classList.toggle("expand");
        });
    </script>

@endsection
