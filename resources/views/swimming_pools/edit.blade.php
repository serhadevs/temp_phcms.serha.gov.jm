@extends('partials.layouts.layout')

@section('title', 'Edit Swimming Pool')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.messages')
        <div class="container-fluid mb-4">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted">
                        Swimming Pool Application {{ $application->firstname }} {{ $application->lastname }}
                    </h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('swimming-pools.update', ['id' => $application->id]) }}" method="POST">
                        @method('PUT')
                        @csrf
                        @include('partials.forms.swimming_pool_app_form')
                        <input type="text" class="form-control" value="{{ $is_edit }}" hidden>
                        <button class="btn btn-warning mt-4" onclick="enableEditting()" type="button" id="editBtn">
                            Edit Application
                        </button>
                        <button class="btn btn-primary mt-4" type="button" onclick="showLoading(this)" style="display:none"
                            id="updateBtn">
                            Update Application
                        </button>
                    </form>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h4 class="text-muted">
                                Edit Transactions
                            </h4>
                        </div>
                        <div class="card-body">
                            @include('partials.tables.edit_transactions_table')
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="/swimming-pools/filter/0" class="btn btn-danger">
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
        @include('partials.messages.loading_message')
    </div>
    <script>
        window.onload = () => {
            if ({{ json_encode($is_edit) }} == '1') {
                enableEditting();
            }

            if (document.querySelectorAll('.text-danger')[0]) {
                enableEditting();
            }
        }

        function enableEditting(element) {
            document.querySelector('input[name=firstname]').removeAttribute('disabled');
            document.querySelector('input[name=middlename]').removeAttribute('disabled');
            document.querySelector('input[name=lastname]').removeAttribute('disabled');
            document.querySelector('input[name=swimming_pool_address]').removeAttribute('disabled');
            document.getElementById('edit_reason_div').style.display = '';
            document.getElementById('updateBtn').style.display = "";
            document.getElementById('editBtn').style.display = "none";
        }
    </script>
@endsection
