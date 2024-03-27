<table class="table table-striped no-warp" id="outstanding_results" style="width:100%">
    <thead>
        <tr>
            <th>App No.</th>
            <th>Name</th>
            @if ($app_type_id == '3')
                <th>Category</th>
            @endif
            <th>Address</th>
            @if ($app_type_id == '3')
                <th>Visit Purpose</th>
            @endif
            @if ($app_type_id == '6')
                <th>Bed Capacity</th>
            @endif
            <th>Overall Score</th>
            <th>Critical Score</th>
            <th>Comments</th>
            <th>Inspection Date</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tourist_ests as $est)
            <tr>
                <td>{{ $application->id }}</td>
                <td>
                    @if ($app_type_id == '3' || $app_type_id == '6')
                        {{ $application->establishment_name }}
                    @endif
                    @if ($app_type_id == '5')
                        {{ $application->firstname . ' ' . $application->middlename . ' ' . $application->last_name }}
                    @endif
                </td>
                @if ($app_type_id == '3')
                    <td>{{ $application->establishmentCategory?->name }}</td>
                @endif
                <td>
                    @if ($app_type_id == '3' || $app_type_id == '6')
                        {{ $application->establishment_address }}
                    @endif
                    @if ($app_type_id == '5')
                        {{ $application->swimming_pool_address }}
                    @endif
                </td>
                @if ($app_type_id == '3')
                    <td>{{ $application->testResult->visit_purpose }}</td>
                @endif
                @if ($app_type_id == '6')
                    <td>{{ $application->bed_capacity }}</td>
                @endif
                <td>{{ $application->testResult?->overall_score }}</td>
                <td>{{ $application->testResult?->critical_score }}</td>
                <td>{{ $application->testResult?->comments }}</td>
                <td>{{ $application->testResult?->test_date }}</td>
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
