@extends('partials.layouts.layout')


@section('title', 'Establishments By Zone')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <div class="card">
                <h2 class="card-header"> Establishments By Zone </h2></h2>
                <div class="card-body">
                 @include('partials.tables.estByZone')
                </div>
                <div class="card-footer">
                    <a href="{{ route('reports.establishment.zone') }}" class="btn btn-danger">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
