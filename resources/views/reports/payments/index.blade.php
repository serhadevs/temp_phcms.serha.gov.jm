@extends('partials.layouts.layout')

@section('title', 'Payment Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">
            <h1>Payment Report</h1>
            <div class="card">
                <div class="card-body">
                    <form action={{ route('reports.payment.show') }} method="POST">
                        @csrf
                        @method("POST")
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
                                <input type="date" class="form-control" name="ending_date">
                            </div>
                        </div>
                        <button class="btn btn-success mt-3" type="submit">
                            Generate Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
