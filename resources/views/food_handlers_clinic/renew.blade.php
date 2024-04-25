@extends('partials.layouts.layout')

@section('title', 'Renew Establishment Clinic')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="conatainer-fluid">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">Renew Clinic {{ $application->name }}</h2>
                    <hr>
                    <form action="{{ route('food-handlers-clinic.renew') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mt-3">
                            <label for="" class="form-label">Establishment Name</label>
                            <input type="text" class="form-control" name="name"
                                value="{{ old('name') ? old('name') : $application?->name }}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <input type="text" class="mt-3 form-control" name="old_app_id" value="{{ $application->id }}">
                        <div class="mt-3">
                            <label for="" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address"
                                value="{{ old('address') ? old('address') : $application->address }}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Telephone Number</label>
                                <input type="text" class="form-control" name="telephone"
                                    value="{{ old('address') ? old('address') : $application->address }}" id="telephone">
                                @error('telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Fax Number</label>
                                <input type="text" name="fax_no" class="form-control"
                                    value="{{ old('fax_no') ? old('fax_no') : $application->fax_no }}" id="fax_no">
                                @error('fax_no')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" name="contact_person"
                                value="{{ old('contact_person') ? old('contact_person') : $application->contact_person }}"
                                oninput="this.value = this.value.toUpperCase()">
                            @error('contact_person')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">No. of Emloyees</label>
                            <input type="text" class="form-control" name="no_of_employees"
                                value="{{ old('no_of_employees') ? old('no_of_employees') : $application->no_of_employees }}">
                            @error('no_of_employees')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Proposed Exercise Date</label>
                                <input type="date" class="form-control" name="proposed_date"
                                    value="{{ old('proposed_date') }}">
                                @error('proposed_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Proposed Exercise Time</label>
                                <input type="text" class="form-control" name="proposed_time"
                                    value="{{ old('proposed_time') }}" oninput="this.value = this.value.toUpperCase()">
                                @error('proposed_time')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Application Date</label>
                            <input type="date" class="form-control" name="application_date"
                                value="{{ old('application_date') }}">
                            @error('application_date')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-primary mt-4" type="submit">
                            Submit Application
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
