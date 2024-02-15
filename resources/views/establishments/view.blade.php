@extends('partials.layouts.layout')

@section('title', 'Establishments')

@section('content')
@include('partials.sidebar._sidebar')

<div class="main">
    @include('partials.navbar._navbar')

    <main class="content px-3 py-4">
        <div class="container-fluid">
            <div class="mb-3">
                <h3 class="fw-bold fs-4 mb-3">Show All Applications</h3>
                <div class="row mb-2">
                    <div class="card shadow">
                        <div class="card-body">
                           @include('tables.food_est_table')
                        </div> 
                    </div>
                    
                </div>
            </div>
        </div>
    </main>
</div>

@endsection