@extends('partials.layouts.layout')

@section('title', 'Outstanding Payment Cancel')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <div class="card">
                <div class="card-header text-muted">
                    <h2 class="text-muted">Outstanding Cancellations</h2>
                </div>
                <div class="card-body">
                    @include('partials.tables.outstanding_payment_cancellations')
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endsection