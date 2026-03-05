@extends('partials.layouts.layout')

@section('title', ' - Collected Card Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            {{-- @include('partials.messages.table_loading') --}}
            <div class="card">
                <h2 class="card-header">Collected Card Report from {{ \Carbon\Carbon::parse($start_date)->format('F d,Y') }}
                    to {{ \Carbon\Carbon::parse($end_date)->format('F d, Y') }}</h2>
                </h2>
                <div class="card-body">
                    @include('partials.tables.collectedCardReportTable')
                </div>
                <div class="card-footer">

                    <a href="{{ route('reports.collected-cards.index') }}" class="btn btn-danger">Back to Search</a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">New
                        Search</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">New Filter</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('reports.collected-cards.show') }}" method="POST">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                    id="start_date" name="start_date" value="{{ old('start_date') }}">
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                    id="end_date" name="end_date" value="{{ old('end_date') }}">
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" type = "submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
            </form>
        </div>

    </div>
    </div>
    </div>
@endsection

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Select Date Range</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="dateRangeForm" method="POST" action="{{ route('reports.collected-cards.show') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" 
                               value="{{ old('start_date') }}">
                        <div class="invalid-feedback" id="start_date_error"></div>
                    </div>

                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" 
                               value="{{ old('end_date') }}">
                        <div class="invalid-feedback" id="end_date_error"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBtn">Save Changes</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalForm = document.getElementById('dateRangeForm');
    const saveBtn = document.getElementById('saveBtn');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const startDateError = document.getElementById('start_date_error');
    const endDateError = document.getElementById('end_date_error');

    // Function to clear all errors
    function clearErrors() {
        startDateInput.classList.remove('is-invalid');
        endDateInput.classList.remove('is-invalid');
        startDateError.textContent = '';
        endDateError.textContent = '';
    }

    // Function to show error
    function showError(input, errorElement, message) {
        input.classList.add('is-invalid');
        errorElement.textContent = message;
    }

    saveBtn.addEventListener('click', function () {
        // Clear previous errors
        clearErrors();

        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        let hasError = false;

        // Validate start date
        if (!startDate) {
            showError(startDateInput, startDateError, 'Please select a start date.');
            hasError = true;
        }

        // Validate end date
        if (!endDate) {
            showError(endDateInput, endDateError, 'Please select an end date.');
            hasError = true;
        }

        // Validate date range
        if (startDate && endDate && endDate < startDate) {
            showError(endDateInput, endDateError, 'End date cannot be earlier than start date.');
            hasError = true;
        }

        // If no errors, submit the form
        if (!hasError) {
            modalForm.submit();
        }
    });

    // Clear error on input change
    startDateInput.addEventListener('change', function() {
        if (this.value) {
            this.classList.remove('is-invalid');
            startDateError.textContent = '';
        }
    });

    endDateInput.addEventListener('change', function() {
        if (this.value) {
            this.classList.remove('is-invalid');
            endDateError.textContent = '';
        }
    });
});
</script>

