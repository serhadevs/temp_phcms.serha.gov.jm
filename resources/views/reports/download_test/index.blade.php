@extends('partials.layouts.layout')

@section('title', 'Download Test')

@section('content')
    @include('partials.sidebar._sidebar')
    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3>Download</h3>
                </div>
                <div class="card-body">
                    <table id="currentUsers" class="display table nowrap table-sm table-bordered"
                        style="width:100%;max-width:100%">
                        <thead>
                            <tr>
                                <th>Download ID</th>
                                <th>Download Number</th>
                                <th>Actual Download Number</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($downloads as $download)
                                <tr>
                                    <td>{{ $download->id }}</td>
                                    <td>{{ $download->application_amount }}</td>
                                    <td>{{ $download->zipped_applications_count }}</td>
                                    <td>{{ $download->application_amount == $download->zipped_applications_count ? 'Correct' : 'Error' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
                </div>
            </div>




            <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
            <link rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">


            <script>
                new DataTable('#currentUsers', {
                    responsive: true,
                    scrollX: true,
                    aLengthMenu: [
                        [parseInt(10), parseInt(25), parseInt(50), parseInt(75), parseInt(100), parseInt(500),
                            parseInt(1000), parseInt(5000), parseInt(-1)
                        ],
                        [10, 25, 50, 75, 100, 500, 1000, 5000, "All"]
                    ],
                });
            </script>
        </div>
    </div>
@endsection
