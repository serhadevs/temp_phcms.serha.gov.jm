@extends('partials.layouts.layout')

@section('title', 'Add Tourist Establishment Managers')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">Add Manager to {{ $establishment_name }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('tourist-establishment.managers.store', ['id' => $tourist_est_id]) }}"
                        method="POST">
                        @csrf
                        @method('POST')
                        @include('partials.forms.tourist_est_managers_form')
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">
                                    *
                                </span>
                                Reason for edit
                            </label>
                            <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
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
