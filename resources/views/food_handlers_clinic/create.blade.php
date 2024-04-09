@extends('partials.layouts.layout')

@section('title', 'New Food Handlers CLinic')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Create New Food Handler's Clinic
                    </h2>
                    <hr>
                    <form action="{{ route('food-handlers-clinic.store') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mt-3">
                            <label for="" class="form-label">Establishment Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" oninput="this.value = this.value.toUpperCase()">
                            @error('name')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="{{ old('address') }}" oninput="this.value = this.value.toUpperCase()">
                            @error('address')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="row mt-3">
                            <div class="col">
                                <label for="" class="form-label">Telephone Number</label>
                                <input type="text" class="form-control" name="telephone" value="{{ old('telephone') }}"
                                    id="telephone">
                                @error('telephone')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Fax Number</label>
                                <input type="text" name="fax_no" class="form-control" value="{{ old('fax_no') }}"
                                    id="fax_no">
                                @error('fax_no')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Contact Person</label>
                            <input type="text" class="form-control" name="contact_person"
                                value="{{ old('contact_person') }}" oninput="this.value = this.value.toUpperCase()">
                            @error('contact_person')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">No. of Emloyees</label>
                            <input type="text" class="form-control" name="no_of_employees"
                                value="{{ old('no_of_employees') }}">
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
        <script src="https://unpkg.com/imask"></script>
        <script>
            const telephone = document.getElementById('telephone');
            const fax_no = document.getElementById('fax_no');

            const maskOptions2 = {
                mask: '+1(000)000-0000'
            }

            const mask1 = IMask(telephone, maskOptions2);
            const mask2 = IMask(fax_no, maskOptions2);
        </script>
    </div>
@endsection
