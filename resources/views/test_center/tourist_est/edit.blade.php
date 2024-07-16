@extends('partials.layouts.layout')

@section('title', 'Processed Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid">
            <div class="card">
                <h2 class="text-muted card-header">
                    {{ isset($is_view) ? 'View' : 'Edit' }} Tourist Inspection Result for
                    {{ $application->establishment_name }}
                </h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">Establishment Name</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $application->establishment_name }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Office First Name</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $application->officer_firstname }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Office Last Name</label>
                            <input type="text" class="form-control" disabled
                                value="{{ $application->officer_lastname }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Establishment Address</label>
                        <input type="text" class="form-control" disabled
                            value="{{ $application->establishment_address }}">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Bed Capacity</label>
                        <input type="text" class="form-control" value="{{ $application->bed_capacity }}" disabled>
                    </div>
                    <form
                        action="{{ route('test-results.tourist-establishments.update', ['id' => $application->testResults?->id]) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        @include('partials.forms.test_results_tourist_est_form')
                        <div class="mt-3" style="{{ isset($is_view) ? 'display:none' : '' }}" id="edit_div">
                            <label for="" class="form-label">
                                <span class="text-danger fw-bold">*</span>
                                Reason for edit
                            </label>
                            <textarea name="edit_reason" class="form-control">{{ old('edit_reason') }}</textarea>
                            @error('edit_reason')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <button class="btn btn-primary mt-4" type="button"
                            style="{{ isset($is_view) ? 'display:none' : '' }}" onclick="showLoading(this)" id="updateBtn">
                            Update Test Results
                        </button>
                        <button class="btn btn-warning mt-4" type="button"
                            style="{{ !isset($is_view) ? 'display:none' : '' }}" onclick="allowEdit()" id="editBtn">
                            Edit Test Results
                        </button>
                    </form>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="text-muted">
                                Transactions
                            </h4>
                        </div>
                        <div class="card-body">
                            @include('partials.tables.edit_transactions_table')
                        </div>
                    </div>
                </div>
            </div>
            @include('partials.messages.loading_message')
        </div>
    </div>
    <script>
        window.onload = () => {
            if (document.querySelectorAll('p.text-danger')[0]) {
                allowEdit();
            }
        }

        function allowEdit() {
            document.querySelectorAll('.editable-fields').forEach((element) => {
                element.removeAttribute('disabled');
            });
            document.getElementById('updateBtn').style.display = "";
            document.getElementById('editBtn').style.display = "none";
            document.getElementById('edit_div').style.display = "";
        }
    </script>
@endsection
