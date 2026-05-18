@extends('partials.layouts.layout')

@section('content')
@section('title', 'Product Key')
@include('partials.sidebar._sidebar')

<div class="main">
    @include('partials.navbar._navbar')

    <div class="container-fluid mt-4">

        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">ID Pro 2.5 Product License</h4>

                <a href="{{ route('dashboard.dashboard') }}" class="btn btn-light btn-sm fw-bold">
                    ← Back to Dashboard
                </a>
            </div>

            <div class="card-body">

                @if ($license)
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Product Name</label>
                            <div class="form-control bg-light">
                                {{ $license->product_name }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">License Key</label>
                            <div class="form-control bg-light text-success fw-bold">
                                {{ $license->license_key }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Client Name</label>
                            <div class="form-control bg-light">
                                {{ $license->client_name }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Client Email</label>
                            <div class="form-control bg-light">
                                {{ $license->client_email }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Max Activations</label>
                            <div class="form-control bg-light">
                                {{ $license->max_activations }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">License Type</label>
                            <div class="form-control bg-light text-danger fw-bold">
                                Perpetual License
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Status</label>
                            <div class="form-control bg-light text-success fw-bold">
                                The license is currently activate.
                            </div>
                        </div>

                    </div>
                @else
                    <div class="alert alert-warning">
                        No license found.
                    </div>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
