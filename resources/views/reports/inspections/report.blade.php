@extends('partials.layouts.layout')

@section('title', 'Generated Inspections Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">
                        Generated Inspections Report
                    </h2>
                </div>
                <div class="card-body">
                    @include('partials.tables.inspections_report')
                </div>
            </div>
        </div>
    </div>
@endsection
