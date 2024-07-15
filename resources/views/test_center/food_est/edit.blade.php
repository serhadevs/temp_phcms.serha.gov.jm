@extends('partials.layouts.layout')

@section('title', 'Edit Test Results')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">
                        Edit Food Est. Results {{ $application->establishment_name }}
                    </h2>
                </div>
                <div class="card-body">
                    <div class="">
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
                        <div class="mt-3" id="reason_div" style="{{ isset($is_view) ? 'display:none' : '' }}">
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
                        <a href="/test-results/food-establishments/filter/0" class="btn btn-danger mt-3">Back</a>
                        <button class="btn btn-warning mt-3" type="button" onclick="makeEditable()"
                            style="{{ !isset($is_view) ? 'display:none' : '' }}" id="editBtn">
                            Edit Results
                        </button>
                        <button class="btn btn-primary mt-3" type="button" onclick="showLoading(this)"
                            style="{{ isset($is_view) ? 'display:none' : '' }}" id="updateBtn">
                            Update Results
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
                    makeEditable();
                }
            }

            function makeEditable() {
                document.querySelectorAll('.editable-fields').forEach((element) => {
                    element.removeAttribute('disabled');
                });
                document.getElementById('updateBtn').style.display = "";
                document.getElementById('editBtn').style.display = "none";
                document.getElementById('reason_div').style.display = "";
            }
        </script>
    </div>
@endsection
