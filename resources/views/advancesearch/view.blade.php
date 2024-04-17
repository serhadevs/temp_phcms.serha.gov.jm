@extends('partials.layouts.layout')

@section('title', 'Advanced Search Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-muted">
                        Search results of Advanced Search
                    </h3>
                </div>
                <div class="card-body">
                    @if ($module == '1')
                        @include('partials.tables.food_handlers_permits_table')
                    @elseif($module == 2)
                        @include('partials.tables.food_handlers_clinics_table')
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
