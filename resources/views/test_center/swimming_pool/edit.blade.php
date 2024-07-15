@extends('partials.layouts.layout')

@section('title', 'Edit Swimming Pool Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid">
            <div class="card">
                <h2 class="card-header text-muted">
                    {{ isset($is_view) ? 'View' : 'Edit' }} {{ $application->firstname . ' ' . $application->lastname }}
                    Swimming Pool Results
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
                        <label for="" class="form-label">Swimming Pool Address</label>
                        <input type="text" class="form-control" disabled
                            value="{{ $application->swimming_pool_address }}">
                    </div>
                    <form
                        action="{{ route('test-results.swimming-pools.update', ['id' => $application->testResults?->id]) }}"
                        method="POST">
                        @method('PUT')
                        @csrf
                        @include('partials.forms.swimming_pool_tresults_form')
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
        @include('partials.messages.loading_message')
    </div>
@endsection
