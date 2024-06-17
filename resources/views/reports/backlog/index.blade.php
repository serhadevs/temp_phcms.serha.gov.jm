@extends('partials.layouts.layout')

@section('title', ' - Applications By Category Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <div class="card">
                <h2 class="card-header">Applications By Category Report from {{ \Carbon\Carbon::parse($start_date)->format('F d,Y') }} to {{ \Carbon\Carbon::parse($end_date)->format('F d, Y') }}</h2></h2>
                <div class="card-body">
                    @include('partials.tables.backlog_table')
                </div>
                <div class="card-footer">
                    <a href="{{ route('reports.appcount.create') }}" class="btn btn-danger">Back to Search</a>
                </div>
            </div>
        </div>
    </div>
@endsection
