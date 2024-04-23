@extends('partials.layouts.layout')

@section('title', 'Summary Report')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <h2 class="text-muted mb-2">Summary Report</h2>
            <div class="card">
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
