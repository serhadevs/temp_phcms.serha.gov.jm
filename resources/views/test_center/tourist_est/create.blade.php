@extends('partials.layouts.layout')

@section('title', 'Processed Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">
            <h2 class="text-muted">
                Create Tourist Inspection Result
            </h2>
            <div class="row mt-3">
                <div class="col">
                    <label for="" class="form-label">Establishment Name</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col">
                    <label for="" class="form-label">Office First Name</label>
                    <input type="text" class="form-control">
                </div>
                <div class="col">
                    <label for="" class="form-label">Office Last Name</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="mt-3">
                <label for="" class="form-label">Establishment Address</label>
                <input type="text" class="form-control">
            </div>
            <div class="mt-3">
                <label for="" class="form-label">Bed Capacity</label>
                <input type="text" class="form-control">
            </div>
            @include('partials.forms.test_result_ests')
        </div>
    </div>
@endsection
