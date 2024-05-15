@extends('partials.layouts.layout')

@section('title', 'Food Establishments Inspections')
@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <main class="content px-3 py-4">
            <div class="container-fluid">
                @include('partials.messages.table_loading')
                <div class="card">
                    <h2 class="card-header">
                        <div class="row">
                            <div class="col">
                                <a href = "/dashboard" class="btn btn-primary">Back</a>  
                               Inspections  
                           </div>
                        </div>
                    </h2>
                    <div class="card-body">
                      @include('partials.messages.messages')
                        <div class="row justify-content-between mb-3">
                            <div class="col">
                               
                            </div>
                            <div class="col-auto no-wrap">
                                <div class="row">
                                    <div class="col">
                                        <a class="btn btn-success text-nowrap" href="/food-establishments/create">
                                            Create New Application
                                        </a>
                                    </div>
                                    <div class="col">
                                        <div class="dropdown">
                                            <button class="btn btn-primary dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                Filter Inspections
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="/inspections/filter/0">Today</a>
                                                </li>
                                                <li><a class="dropdown-item"
                                                        href="/inspections/filter/1">Yesterday</a>
                                                </li>
                                                <li><a class="dropdown-item" href="/inspections/filter/7">Last
                                                        Week</a></li>
                                                <li><a class="dropdown-item" href="/inspections/filter/30">Last
                                                        Month</a>
                                                </li>
                                                <li><a class="dropdown-item" href="/inspections/filter/90">Last 3
                                                        month</a>
                                                </li>
                                                <li><button class="dropdown-item" href="#"
                                                        onclick="showSearchBar()">Custom</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('food-establishment.filter.custom') }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="row text-center justify-content-md-center" id="search-row" style="display:none">
                                <div class="col col-md-3">
                                    <input type="date" class="form-control" placeholder="Starting Date"
                                        name="starting_date" id="starting_date" value="{{ old('starting_date') }}">
                                    <input type="text" class="form-control" id="interval" name="interval"
                                        style="display:none" hidden>
                                    @error('starting_date')
                                        <p class="fw-bold text-danger errors">{{ $message }}</p>
                                    @enderror
                                    @error('interval')
                                        <p class="fw-bold text-danger errors">Interval must be 6 months or less</p>
                                    @enderror
                                </div>
                                To
                                <div class="col col-md-3">
                                    <input type="date" class="form-control" placeholder="Ending Date" name="ending_date"
                                        id="ending_date" value="{{ old('ending_date') }}">
                                    @error('ending_date')
                                        <p class="fw-bold text-danger errors">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col col-md-1">
                                    <button class="btn btn-md btn-success" type="submit">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                        @include('partials.tables.inspections_table')
                    </div>
                </div>
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

                window.onload = () => {
                    errors = document.querySelectorAll(".errors");
                    if (errors[0]) {
                        showSearchBar();
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

                function showSearchBar() {
                    if (document.getElementById("search-row").style.display == "none") {
                        document.getElementById("search-row").style.display = "";
                    } else {
                        document.getElementById("search-row").style.display = "none";
                    }

                }
            </script>
        </main>
    </div>

@endsection
