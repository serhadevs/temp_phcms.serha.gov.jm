@extends("partials.layouts.layout")

@section("title", "Payment Report")

@section("content")
    @include("partials.sidebar._sidebar")
    <div class="main">
        @include("partials.navbar._navbar")
        <div class="container-fluid">
            @include('partials.messages.table_loading')
            <h1>Payment Report</h1>
            <div class="card">
                <div class="card-body">
                    @include("partials.tables.payment_report_table")
                </div>
            </div>
        </div>
    </div>
@endsection