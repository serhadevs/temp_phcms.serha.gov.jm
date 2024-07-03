@extends('partials.layouts.layout')

@section('title', 'Edit Manager Tourist Establishment')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.messages.messages')
        @include('partials.navbar._navbar')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-header text-nowrap">
                    <a href="/tourist-establishments/view/{{ $establishment->id }}" class="btn btn-danger" style="float:left; margin-right:1%;">
                        <i class="bi bi-box-arrow-left"></i>
                        Back
                    </a>
                    <h2 class="text-muted">Edit Manager: {{ $manager->firstname . ' ' . $manager->lastname }} of
                        Establishment
                        {{ $establishment->establishment_name }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('tourist-establishments.manager.update', ['id' => $manager->id]) }}"
                        method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" class="form-control" name="manager_id" value="{{ $manager->id }}">
                        @include('partials.forms.tourist_est_managers_form')
                        <div class="mt-3">
                            <label for="" class="form-label">Reason for edit</label>
                            <textarea class="form-control" name="edit_reason">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
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
