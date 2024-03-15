@extends('partials.layouts.layout')

@section('title', 'Food Establishments')
@section('content')
@include('partials.sidebar._sidebar')

<div class="main">
    @include('partials.navbar._navbar')
    <main class="content px-3 py-4">
        <div class="container">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted mb-3">
                        Processed Food Establishments
                    </h2>
                    @include('partials.tables.processed_food_establishment_table')
                </div>
            </div>
        </div>
    </main>
</div>

@endsection

