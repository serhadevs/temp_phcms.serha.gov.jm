@extends('partials.layouts.layout')

@section('title', 'General Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <div class="card">
                <div class="card-header">
                    <a class="btn btn-danger" style="float:left; margin-right:1%" href="/reports/general-report">
                        <i class="bi bi-box-arrow-left"></i>
                        Back
                    </a>
                    <h2 class="text-muted">Generated General Report</h2>
                </div>
                <div class="card-body">
                    @if ($application_type == '1')
                        <?php
                        $permit_applications = $applications;
                        ?>
                        @include('partials.tables.food_handlers_permits_table')
                    @elseif($application_type == '2')
                        @include('partials.tables.barber_cosmet_table')
                    @elseif($application_type == '3')
                        <?php
                        $app_type_id = 3;
                        ?>
                        @include('partials.tables.test_results_est')
                    @elseif($application_type == '4')
                        <?php
                        $food_clinics = $applications;
                        ?>
                        @include('partials.tables.food_handlers_clinics_table')
                    @elseif($application_type == '5')
                        @include('partials.tables.swimming_pools')
                    @elseif($application_type == '6')
                        @include('partials.tables.tourist_establishments_table')
                    @endif
                </div>
            </div>
        </div>
        <script>
            window.onload = () => {
                buttons = document.querySelectorAll("div.dt-buttons button");
                buttons.forEach((element) => {
                    element.classList.add("btn");
                    element.classList.add("btn-secondary")
                })
            }
        </script>
        <style>
            div.dt-buttons {
                width: 50%;
                float: left;
            }

            .dataTables_info {
                width: 50%;
                float: left;
            }
        </style>
    </div>
@endsection
