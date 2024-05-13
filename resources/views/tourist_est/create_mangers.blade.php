@extends('partials.layouts.layout')

@section('title', 'Add Tourist Establishment Managers')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">Add Manager to {{ $establishment_name }}</h2>
                    <hr>
                    <form action="{{ route('tourist-establishment.managers.store') }}" method="POST">
                        <input type="hidden" class="form-control" name="tourist_est_id" value="{{ $tourist_est_id }}">
                        @csrf
                        @method('POST')
                        @include('partials.forms.tourist_est_managers_form')
                        <button class="btn-primary btn mt-4" type="button" onclick="showLoading(this)">
                            Add Manager
                        </button>
                    </form>
                </div>
            </div>
            @include('partials.messages.loading_message')
        </div>
    </div>
@endsection
