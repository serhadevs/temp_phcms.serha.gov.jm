@extends('partials.layouts.layout')

@section('title', 'Increase Employee Count Request')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">
                        Request for Edit No. Of Employees
                    </h2>
                </div>
                <div class="card-body">
                    @include('partials.tables.requests_edit_employees')
                </div>
            </div>
        </div>

    </div>
@endsection
