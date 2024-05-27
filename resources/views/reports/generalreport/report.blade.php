Kian
@extends('partials.layouts.layout')

@section('title', 'General Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">Generated General Report</h2>
                </div>
                <div class="card-body">
                    @if ($application_type == '1')
                        <?php
                        $permit_applications = $applications;
                        ?>
                        @include('partials.tables.food_handlers_permits_table')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
