<table class="table table-bordered table-striped table-sm" id="outstanding_results" style="width:100%">
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
        @foreach ($outstanding_permits as $application)
            <tr>
                <td><a href="/test-results/permits/{{ $application->permitApplications?->id }}/create"
                        class="btn btn-primary btn-sm">Select</a></td>
                <td>{{ $application->permitApplications?->id }}</td>
                <td>{{ strtoupper($application->permitApplications?->permitCategory?->name) }}</td>
                <td>{{ strtoupper($application->permitApplications?->firstname) }}</td>
                <td>{{ strtoupper($application->permitApplications?->middlename) }}</td>
                <td>{{ strtoupper($application->permitApplications?->lastname) }}</td>
                <td>{{ strtoupper($application->permitApplications?->address) }}</td>
                <td>{{ $application->permitApplications?->date_of_birth }}</td>
                <td>{{ strtoupper($application->permitApplications?->gender) }}</td>
                <td>{{ $application->created_at }}</td>
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
        initComplete: function() {
            loading.close()
        },
        responsive: true
    });
</script>
