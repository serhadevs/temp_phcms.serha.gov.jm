@extends('partials.layouts.layout')

@section('title', 'Transaction Report Index')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <form action="{{ route('reports.printed-cards.show') }}" method="POST">
                @csrf
                @method('POST')
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-muted">
                            Create Printed Cards/Licenses Report
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">Printed Start Date</label>
                                <input type="date" class="form-control" id="starting_date" name="start_date"
                                    value="{{ old('start_date') }}">
                                <input type="text" id="interval" class="form-control" name="interval" hidden
                                    value="{{ old('interval') }}">
                                @error('start_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                                @error('interval')
                                    <p class="text-danger">Interval must be 6 months or less</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Printed End Date</label>
                                <input type="date" class="form-control" name="end_date" id="ending_date"
                                    value="{{ old('end_date') }}">
                                @error('end_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <div class="mt-3">
                                    <label for="" class="form-label">Application Type</label>
                                    <select name="application_type_id" class="form-select"
                                        onchange="applicationType(this.value)">
                                        <option value="1" {{ old('application_type_id') == '1' ? 'selected' : '' }}>
                                            Food Handlers Permit</option>
                                        <option value="3" {{ old('application_type_id') == '3' ? 'selected' : '' }}>
                                            Food Establishments</option>
                                    </select>
                                    @error('application_type_id')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="col">
                                <div class="mt-3" id="food_clinic_div">
                                    <label for="" class="form-label">Establishment Clinic Name</label>
                                    <input type="text" class="form-control" list="clinicDataList"
                                        name="establishment_clinic_name" placeholder="Type to search Onsite Clinic..."
                                        value="{{ old('establishment_clinic_name') }}">
                                    <datalist id="clinicDataList">
                                        @foreach ($est_clinics as $clinic)
                                            <option value="{{ $clinic->name }}">{{ $clinic->name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="mt-3" id="food_establishment_div" style="display:none">
                                    <label for="" class="form-label">Food Establishment Name</label>
                                    <input type="text" class="form-control" name="food_establishment_name"
                                        placeholder="Type to search Food Establishment.." list="establishmentDataList"
                                        value="{{ old('food_establishment_name') }}">
                                    <datalist id="establishmentDataList">
                                        @foreach ($food_ests as $est)
                                            <option value="">{{ $est->establishment_name }}</option>
                                        @endforeach
                                    </datalist>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Test Date</label>
                            <input type="date" class="form-control" name="test_date" value="{{ old('test_date') }}">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-success" type="submit">
                            Generate Report
                        </button>
                    </div>
                </div>
            </form>

        </div>
        <script>
            $(document).ready(function() {
                $('#starting_date').change(function() {
                    calcInterval();
                })

                $('#ending_date').change(function() {
                    calcInterval();
                })

                $('#starting_date').keyup(function() {
                    calcInterval();
                })

                $('#ending_date').keyup(function() {
                    calcInterval();
                })
            })

            function applicationType(val) {
                if (val == 1) {
                    document.getElementById('food_clinic_div').style.display = "";
                    document.getElementById('food_establishment_div').style.display = 'none';
                } else if (val == 3) {
                    document.getElementById('food_clinic_div').style.display = "none";
                    document.getElementById('food_establishment_div').style.display = '';
                }
            }

            function calcInterval() {
                if (document.getElementById('starting_date').value && document.getElementById('ending_date').value) {
                    var starting_date = new Date(document.getElementById("starting_date").value);
                    var ending_date = new Date(document.getElementById("ending_date").value);
                    var datediff = (ending_date.getMonth() - starting_date.getMonth()) + (12 * (ending_date.getFullYear() -
                        starting_date.getFullYear()));
                    document.getElementById('interval').value = datediff;
                }
            }
        </script>
    @endsection
