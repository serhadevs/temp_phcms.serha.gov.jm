@extends("partials.layouts.layout")

@section("title", "Payment Report")

@section("content")
    @include("partials.sidebar._sidebar")
    <div class="main">
        @include("partials.navbar._navbar")
        <div class="container-fluid">
            @include('partials.messages.table_loading')
           
            <div class="card">
                <div class="card-header">
                    <h3 class="text-muted">Payment Report</h3>
                </div>
                <div class="card-body">
                    @include("partials.tables.payment_report_table")
                </div>
                <div class="card-footer">
                    <a href="{{ route('reports.payment.index') }}" class="btn btn-danger">Back to Search</a>
                </div>
            </div>
        </div>
    </div>
@endsection