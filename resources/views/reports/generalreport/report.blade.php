@extends("partials.layouts.layout")

@section("title", "General Report")

@section("content")
    @include("partials.sidebar._sidebar")
    <div class="main">
        @include("partials.navbar._navbar")
        <div class="container">
            <h1>General Report</h1>
            <div class="card">
                <div class="card-body">
                    {{ $data }}
                </div>
            </div>
        </div>
    </div>
@endsection