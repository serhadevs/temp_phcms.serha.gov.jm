@extends('partials.layouts.layout')

@section('title', 'Appointments')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            @include('partials.messages.messages')
            <div class="card">
                <div class="card-header text-muted">
                    <h3>Appointments for {{ \Carbon\Carbon::parse($app_date)->format('d F Y') }}</h3>
                </div>
                <div class="card-body">
                    @include('partials.tables.appointmentstable')
                </div>
                <div class="card-footer">
                    <a href = "{{ route('appointments.index') }}" class="btn btn-danger">Back to Dashboard</a>
                </div>

            </div>
        </div>
    </div>
@endsection
