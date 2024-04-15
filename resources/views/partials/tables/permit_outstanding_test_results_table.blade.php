<table class="table table-striped no-warp" id="outstanding_results" style="width:100%">
    <thead>
        <tr>
            <th>Options</th>
            <th>App #</th>
            <th>Category</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>Date of Birth</th>
            <th>Gender</th>
            <th>Payment Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach (json_decode($outstanding) as $application)
            <tr>
                <td><a href="/test-results/permits/{{ $application->app_number }}/create" class="btn btn-primary btn-sm">Select</a></td>
                <td>{{ $application->app_number }}</td>
                <td>{{ $application->category }}</td>
                <td>{{ $application->firstname }}</td>
                <td>{{ $application->middlename }}</td>
                <td>{{ $application->lastname }}</td>
                <td>{{ $application->address }}</td>
                <td>{{ $application->date_of_birth }}</td>
                <td>{{ $application->gender }}</td>
                <td>{{ $application->payment_date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<script>
    new DataTable('#outstanding_results', {
        scrollX: true,
        responsive: true
    });
</script>
