@extends('partials.layouts.layout')

@section('title', 'Generated Transactions Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    @include('partials.messages.table_loading')
                    <h2 class="text-muted">
                        Generated Edit Transactions Report
                    </h2>
                </div>
                <div class="card-body">
                    @include('partials.tables.edit_transactions_report')
                </div>
                <div class="card-footer">
                    <a class="btn btn-danger" href="/report/transactions">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection