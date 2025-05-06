@extends('partials.layouts.layout')

@section('title', 'Summary Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h2 class="text-muted mb-2">Summary Report</h2>
                </div>
                <div class="card-body">
                    <form action={{ route('report.summary.show') }} method="POST">
                        @csrf
                        @method('POST')
                        <div class="row">
                            <div class="col">
                                <label for="" class="form-label">
                                    Start Date
                                </label>
                                <input type="date" class="form-control" name="starting_date">
                            </div>
                            <div class="col">
                                <label for="" class="form-label">
                                    End Date
                                </label>
                                <input type="date" class="form-control" name="ending_date" max="date">
                            </div>
                            <div class="col"
                                style="display:{{ in_array(auth()->user()->facility_id, [1,2,3]) ? (in_array(auth()->user()->role_id, [1, 4, 9]) ? '' : 'none') : 'none' }}">
                                {{-- <div class="col"> --}}
                                <label for="" class="form-label">Payment Type</label>
                                <select name="payment_type_id" id="" class="form-select">
                                    @foreach ($payment_types as $payment_type)
                                        <option value="{{ $payment_type->id }}"
                                            {{ old('payment_type_id') == $payment_type->id ? 'selected' : '' }}>
                                            {{ $payment_type->name }}</option>
                                    @endforeach
                                    <option value="">Combined Payments</option>
                                </select>
                                {{-- </div> --}}
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
