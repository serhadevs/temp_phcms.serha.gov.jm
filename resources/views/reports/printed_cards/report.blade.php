@extends('partials.layouts.layout')

@section('title', 'Generated Printed Cards Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    @include('partials.messages.table_loading')
                    <h2 class="text-muted">
                        Generated Printed Cards Report
                    </h2>
                </div>
                <div class="card-body">
                    @include('partials.tables.printed_cards_report')
                </div>
                <div class="card-footer">
                    <a class="btn btn-danger" href="/reports/printed-cards">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection