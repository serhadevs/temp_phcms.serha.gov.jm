@extends('partials.layouts.layout')

@section('title', ' Waiver Approvals')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        @include('partials.messages.confirmmessage')

        <main class="content px-3 py-4">
            <div class="container-fluid">
                <div class="mb-5">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header text-muted">
                                    <h2>Waiver Approvals</h2>
                                </div>
                                <div class="card-body">
                                    @include('waiver_approvals.partials.waiverTable')
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@endsection
