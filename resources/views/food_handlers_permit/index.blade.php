@extends('partials.layouts.layout')

@section('title', 'Food Handlers Permit')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            @include('partials.messages.messages')
        
            <div class="card">
                <div class="card-header">
                    <div class="row justify-content-between align-items-center">
                        <!-- Back button and Title -->
                        <div class="col-12 col-md-6 d-flex align-items-center mb-3 mb-md-0">
                            <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger me-2">
                                <i class="bi bi-box-arrow-left"></i> Back
                            </a>
                            <h2 class="text-muted mb-0">All Food Handler's Applications</h2>
                        </div>
        
                        <!-- Create and Filter buttons -->
                        <div class="col-12 col-md-auto d-flex justify-content-end">
                            <a href="{{ route('food_handlers_permit.newApplication') }}" class="btn btn-success me-2">Create Application</a>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Filter Applications
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/permit/filter/0">Today</a></li>
                                    <li><a class="dropdown-item" href="/permit/filter/1">Yesterday</a></li>
                                    <li><a class="dropdown-item" href="/permit/filter/7">Last Week</a></li>
                                    <li><a class="dropdown-item" href="/permit/filter/30">Last Month</a></li>
                                    <li><a class="dropdown-item" href="/permit/filter/90">Last 3 months</a></li>
                                    <li><button class="dropdown-item" type="button" onclick="showSearchBar()">Custom</button></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="card-body">
                    <form action="{{ route('permit.index.custom') }}" method="POST">
                        @csrf
                        @method('POST')
                        <!-- Custom Date Range Filter -->
                        <div class="row text-center justify-content-md-center" id="search-row" style="display:none">
                            <div class="col-12 col-md-3 mb-2">
                                <input type="date" class="form-control" placeholder="Starting Date" name="starting_date" value="{{ old('starting_date') }}" id="starting_date">
                                <input type="text" id="interval" class="form-control" name="interval" hidden value="{{ old('interval') }}">
                                @error('starting_date')
                                    <p class="fw-bold text-danger errors">{{ $message }}</p>
                                @enderror
                                @error('interval')
                                    <p class="fw-bold text-danger errors">Interval must be 6 months or less</p>
                                @enderror
                            </div>
                            <div class="col-12 col-md-auto align-self-center mb-2">To</div>
                            <div class="col-12 col-md-3 mb-2">
                                <input type="date" class="form-control" placeholder="Ending Date" name="ending_date" value="{{ old('ending_date') }}" id="ending_date">
                                @error('ending_date')
                                    <p class="fw-bold text-danger errors">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-12 col-md-1 mb-2">
                                <button class="btn btn-md btn-success w-100" type="submit">Submit</button>
                            </div>
                        </div>
                    </form>
        
                    @include('partials.tables.food_handlers_permits_table')
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
        <script>
            document.addEventListener("DOMContentLoaded", (event) => {
                loading.close();
            });
        </script>
    </div>
@endsection
