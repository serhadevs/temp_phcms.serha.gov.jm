@extends('partials.layouts.layout')

@section('title', 'Payments')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <div class="card">
                <div class="card-header">
                    
                    <div class="row justify-content-between">
                        <div class="col">
                            <h3 class="text-muted">Processed Payments</h3>
                        </div>
                       
                        <div class="col-auto">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Filter Payments
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="/payments/index/filter/0">Today</a></li>
                                    <li><a class="dropdown-item" href="/payments/index/filter/1">Yesterday</a></li>
                                    <li><a class="dropdown-item" href="/payments/index/filter/7">Last Week</a></li>
                                    <li><a class="dropdown-item" href="/payments/index/filter/30">Last Month</a></li>
                                    <li><a class="dropdown-item" href="/payments/index/filter/90">Last 3 month</a>
                                    </li>
                                    <li><button class="dropdown-item" href="#"
                                            onclick="showSearchBar()">Custom</button></li>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('payments.index.filter.custom') }}" method="POST">
                        @csrf
                        @method('POST')
                        <div class="row text-center justify-content-md-center" id="search-row" style="display:none">
                            <div class="col col-md-3">
                                <input type="date" class="form-control" placeholder="Starting Date" name="starting_date"
                                    id="starting_date" value="{{ old('starting_date') }}">
                                @error('starting_date')
                                    <p class="errors" style="color:red; font-weight:bold">{{ $message }}</p>
                                @enderror
                                <input type="text" class="form-control" id="interval" name="interval" style="display:none" hidden value="{{ old('interval') }}"/>
                                @error('interval')
                                    <p class="errors" style="color:red ;font-weight:bold">Interval must be 6 months or less</p>
                                @enderror
                            </div>
                            To
                            <div class="col col-md-3">
                                <input type="date" class="form-control" placeholder="Ending Date" name="ending_date" value="{{ old('ending_date') }}"
                                    id="ending_date">
                                @error('ending_date')
                                    <p class="errors" style="color:red; font-weight:bold">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col col-md-1">
                                <button class="btn btn-md btn-success" type="submit">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                    @include('partials.tables.processed_payments_table')
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
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

            window.onload = () =>{
                errors=document.querySelectorAll(".errors");
                if(errors[0]){
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
            const hamBurger = document.querySelector(".toggle-btn");

            hamBurger.addEventListener("click", function() {
                document.querySelector("#sidebar").classList.toggle("expand");
            });
        </script>
    </div>
@endsection
