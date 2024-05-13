@extends('partials.layouts.layout')

@section('title', 'Edit Manager Tourist Establishment')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">Edit Manager: {{ $manager->firstname . ' ' . $manager->lastname }} of Establishment
                        {{ $establishment_name }}</h2>
                    <form action="{{ route('tourist-establishments.manager.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" class="form-control" name="manager_id" value="{{ $manager->id }}">
                        @include('partials.forms.tourist_est_managers_form')
                        <button type="button" class="btn btn-primary mt-4" onclick="showLoading(this)">
                            Update Manager Information
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
