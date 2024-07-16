@extends('partials.layouts.layout')

@section('title', 'Edit Barber/Cosmet Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid mb-4">
            <div class="card">
                <h2 class="text-muted card-header">
                    Edit Barber/Cosmet Test Results {{ $application->firstname }} {{ $application->lastname }}
                </h2>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <label for="" class="form-label">First Name</label>
                            <input type="text" class="form-control" disabled value="{{ $application->firstname }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Middle Name</label>
                            <input type="text" class="form-control" disabled value="{{ $application->middlename }}">
                        </div>
                        <div class="col">
                            <label for="" class="form-label">Last Name</label>
                            <input type="text" class="form-control" disabled value="{{ $application->lastname }}">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Home Address</label>
                        <input type="text" class="form-control" disabled value="{{ $application->address }}">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Business Address</label>
                        <input type="text" class="form-control" disabled value="{{ $application->employer_address }}">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">Date of Birth</label>
                        <input type="text" class="form-control" disabled value="{{ $application->date_of_birth }}">
                    </div>
                    <div class="mt-3">
                        <label for="" class="form-label">
                            Telephone
                        </label>
                        <input type="text" class="form-control" disabled value="{{ $application->telephone }}">
                    </div>
                    <form
                        action="{{ route('test-results.barber-cosmet.update', ['id' => $application->testResults?->id]) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        @include('partials.forms.barber_cosmet_test_results')
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
        </div>
        @include('partials.messages.loading_message')
    </div>
@endsection
