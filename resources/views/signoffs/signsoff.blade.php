@extends('partials.layouts.layout')

@section('title', 'Sign Off Controller')

@section('content')
@include('partials.sidebar._sidebar')

<div class="main">
    @include('partials.navbar._navbar')

    <main class="content px-3 py-4">
        <div class="container-fluid">
            <div class="mb-3">
                <div class="card shadow">
                    <div class="card-header">
                        <h4>Approved Food Establishments</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <table id="sign_offs" class="table table-bordered" style="width: 100%">
                                <thead>
                                    <th>Permit #</th>
                                    <th>Establishment Name</th>
                                    <th>Sign Off Date</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Zone</th>
                                    <th>Approved By</th>
                                    <th>Options</th>
                                </thead>
                                <tbody>
                                    @foreach ($applications as $applicant )
                                        <tr>
                                            <td>{{ $applicant->permit_no }}</td>
                                            <td>{{ $applicant->establishment_name }}</td>
                                            <td>{{ \Carbon\Carbon::parse($applicant->sign_off_date)->format('d F Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($applicant->expiry_date)->format('d F Y') }}</td>
                                            <td>{{ $applicant->is_grant = 1 ? 'Approved' : 'Awaiting Approval'}}</td>
                                            <td>{{ $applicant->zone }}</td>
                                            <td>{{ $applicant->firstname. " " .$applicant->lastname}}</td>
                                            <td> <a class="btn btn-primary btn-sm" href="/food-establishments/view/{{ $applicant->id }}">
                                                View
                                            </a></td>
                                           
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="#" onclick="history.back();" class="btn btn-danger"> Back to Previous Page</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
</div>


<script>
const hamBurger = document.querySelector(".toggle-btn");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});
</script>
@endsection
<!-- Include jQuery library -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include DataTables scripts -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">

<!-- Include DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- DataTables Initialization -->
<script>
    $(document).ready(function() {
        $('#sign_offs').DataTable({
            scrollX: true,
            // responsive: true
        });
    });
</script>




