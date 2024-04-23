@extends('partials.layouts.layout')

@section('title', ' - Summary Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <h2 class="text-muted mb-2">Summary Report</h2>
            <div class="card">
                <div class="card-body">
                    @include('partials.tables.summary_report_table')
                </div>
            </div>
        </div>
    </div>
@endsection
