@extends('partials.layouts.layout')

@section('title', 'Create Inspection Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <form action="{{ route('reports.inspections.show') }}" method="POST">
                @csrf
                @method('POST')
                <div class="card">
                    <div class="card-header">
                        <h2 class="text-muted">
                            Create Inspections Report
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">Inspections Start Date</label>
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
                                <label for="" class="form-label">Inspections End Date</label>
                                <input type="date" class="form-control" name="end_date" id="ending_date"
                                    value="{{ old('end_date') }}">
                                @error('end_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3">
                            <label for="" class="form-label">Application Type</label>
                            <select name="application_type_id" class="form-select">
                                <option value="All">All Application Types</option>
                                @foreach ($application_types as $app_type)
                                    <option value="{{ $app_type->id }}">{{ $app_type->name }}</option>
                                @endforeach
                            </select>
                            @error('application_type_id')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
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
