@extends('partials.layouts.layout')

@section('title', ' - Applications By Category Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <div class="card">
                <h2 class="card-header">Applications By Category Report</h2>
                <div class="card-body">
                    @include('partials.tables.app_count_category_table')
                </div>
                <div class="card-footer">
                    <a href="{{ route('reports.appcount.create') }}" class="btn btn-danger">Back to Search</a>
                </div>
            </div>
        </div>
    </div>
@endsection
