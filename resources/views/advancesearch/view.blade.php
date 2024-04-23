@extends('partials.layouts.layout')

@section('title', 'Advanced Search Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-muted">
                        Search results of Advanced Search
                    </h3>
                </div>
                <div class="card-body">
                    @if ($module == '1')
                        @include('partials.tables.food_handlers_permits_table')
                    @elseif($module == '2')
                        @include('partials.tables.food_handlers_clinics_table')
                    @elseif($module == '3')
                        @if ($app_type_id == '3')
                            @include('partials.tables.test_results_est')
                        @elseif($app_type_id == '1')
                            @include('partials.tables.permit_processed_test_results_table')
                        @endif
                    @elseif($module == '4')
                        @include('partials.tables.advanced_search_health_interview')
                    @elseif($module == '5')
                        @include('partials.tables.processed_payments_table')
                    @elseif($module == '6')
                        @include('partials.tables.processed_food_establishment_table')
                    @endif
                    <a class="btn btn-danger mt-4" href="/advance-search/create">
                        <i class="bi bi-box-arrow-left"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
