@extends('partials.layouts.layout')

@section('title', 'Outstanding Payment Cancel')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">Outstanding Cancellations</h2>
                    @include('partials.tables.outstanding_payment_cancellations')
                </div>
            </div>
        </div>
    </div>
@endsection