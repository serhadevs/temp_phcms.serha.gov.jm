@extends('partials.layouts.layout')

@section('title', 'Payment Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">

            <div class="card shadow">
                <div class="card-header">
                    <h3 class="text-muted">Payment Report</h3>
                </div>
                <div class="card-body">
                    <form action={{ route('reports.payment.show') }} method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label fw-bold">
                                    Start Date
                                </label>
                                <input type="date"
                                    class="form-control @error('starting_date')
                                    is-invalid
                                @enderror"
                                    name="starting_date">
                                @error('starting_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="" class="form-label fw-bold">
                                    End Date
                                </label>
                                <input type="date"
                                    class="form-control @error('ending_date')
                                    is-invalid
                                @enderror"
                                    name="ending_date" max="{{ date('Y-m-d') }}">
                                @error('ending_date')
                                    <p class="text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col" {{ auth()->user()->facility_id == 3 ? '' : 'hidden' }}>
                                <label for="" class="form-label fw-bold">Payment Type</label>
                                <select name="payment_type_id" id="" class="form-select">
                                    @foreach ($payment_types as $payment_type)
                                        <option value="{{ $payment_type->id }}"
                                            {{ old('payment_type_id') == $payment_type->id ? 'selected' : '' }}>
                                            {{ $payment_type->name }}</option>
                                    @endforeach
                                </select>
                                @error('payment_type_id')
                                    <p class="text-danger">This is a required field</p>
                                @enderror
                            </div>
                        </div>


                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                    <button class="btn btn-success" type="submit">
                        Generate Report
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
