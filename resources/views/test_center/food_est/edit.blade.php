@extends('partials.layouts.layout')

@section('title', 'Edit Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-muted">
                        Edit Food Est. Results {{ $application->establishment_name }}
                    </h2>
                    <hr>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Name</label>
                        <input type="text" class="form-control" value="{{ $application->establishment_name }}" disabled>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Category</label>
                        <input type="text" class="form-control" value="{{ $application->establishmentCategory?->name }}"
                            disabled>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Address</label>
                        <input type="text" class="form-control" value="{{ $application->establishment_address }}"
                            disabled>
                    </div>
                    <form action="{{ route('test-results.food-est.update', ['id' => $result->id]) }}" method="POST">
                        @method('POST')
                        @csrf
                        @include('partials.forms.test_result_ests')
                        <div class="mt-3">
                            <label for="" class="form-label">
                                <span class="fw-bold text-danger">
                                    *
                                </span>
                                Reason for edit
                            </label>
                            <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <a href="/test-results/food-establishments/outstanding/filter/0"
                            class="btn btn-danger mt-3">Back</a>
                        <button class="btn btn-primary mt-3" type="button" onclick="showLoading(this)">
                            Update Results
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
