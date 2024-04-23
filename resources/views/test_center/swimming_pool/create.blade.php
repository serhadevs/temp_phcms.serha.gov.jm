@extends('partials.layouts.layout')

@section('title', 'Processed Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Create Swimming Pool Inspection Result
                    </h2>
                    <div class="row mt-3">
                        <div class="col">
                            <label for="" class="form-label">First Name</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Middle Name</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Last Name</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Swimming Pool Address</label>
                        <input type="text" class="form-control">
                    </div>
                    @include('partials.forms.test_result_ests')
                </div>
            </div>
        </div>
    </div>
@endsection
