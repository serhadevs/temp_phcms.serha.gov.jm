@extends('partials.layouts.layout')

@section('title', 'View Food Handlers Clinic')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Application for
                    </h2>
                    <hr>
                    <form action="" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mt-3">
                            <label for="" class="form-label">Application ID</label>
                            <input type="text" class="form-control" name="id" id="name" readonly value="{{ old('') }}">
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Establishment Address</label>
                            <input type="text" class="form-control" name="address" id="address" disabled>
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Telephone</label>
                                <input type="text" class="form-control" name="telephone" id="telephone" disabled>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Fax Number</label>
                                <input type="text" class="form-control" name="fax_no" id="fax_no" disabled>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Contact Person</label>
                            <input type="text" name="contact_person" class="form-control" id="contact_person" disabled>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">No of Employees</label>
                            <input type="text" name="no_of_employees" class="form-control" id="no_of_employees" disabled>
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">Proposed Date</label>
                                <input type="text" class="form-control" id="proposed_date" name="proposed_date" disabled>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Proposed Time</label>
                                <input type="text" class="form-control" id="proposed_time" name="proposed_time" disabled>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Application Date</label>
                            <input type="text" class="form-control" id="application_date" name="application_date" disabled>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
