@extends('partials.layouts.layout')

@section('title', ' - Onsite Applications')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <div class="card">
                <h2 class="card-header">Onsite Applications Processed from {{ \Carbon\Carbon::parse($start_date)->format('F d,Y') }} to {{ \Carbon\Carbon::parse($end_date)->format('F d, Y') }}</h2>
                <div class="card-body">
                    @if ($module == '1')
                    @include('partials.tables.onsite_report_table')
                    @else
                     @include('partials.tables.onsite_list_table')   
                    @endif
                    
                </div>
                <div class="card-footer">
                    <a href="{{ route('reports.onsite') }}" class="btn btn-danger">Back to Search</a>
                </div>
            </div>
        </div>
    </div>
@endsection
