@extends('partials.layouts.layout')

@section('title', 'Processed Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
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
                <label for="" class="form-label">Home Address</label>
                <input type="text" class="form-control">
            </div>
            <div class="mt-3">
                <label for="" class="form-label">Business Address</label>
                <input type="text" class="form-control">
            </div>
            <div class="mt-3">
                <label for="" class="form-label">Date of Birth</label>
                <input type="text" class="form-control">
            </div>
            <div class="row">
                <div class="col">
                    <label for="" class="form-label">
                        Home Phone
                    </label>
                    <input type="text" class="form-control">
                </div>
                <div class="col">
                    <label for="" class="form-label">Work Phone</label>
                    <input type="text" class="form-control">
                </div>
            </div>
            <form action="">
                <div class="row mt-3">
                    <div class="col">
                        <label for="" class="form-label">Trainer(s)</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col">
                        <label for="" class="form-label">Test Score</label>
                        <input type="number" class="form-control">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col">
                        <label for="" class="form-label">Test Location</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col">
                        <label for="" class="form-label">Test Date</label>
                        <input type="number" class="form-control">
                    </div>
                </div>
                <div class="mt-3">
                    <label for="" class="form-label">Comments</label>
                    <textarea name="" id="" cols="30" rows="10"></textarea>
                </div>
            </form>
        </div>
    </div>
@endsection
