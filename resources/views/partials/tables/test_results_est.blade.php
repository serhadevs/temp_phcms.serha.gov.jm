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
        @foreach ($applications as $application)
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
                    <td>{{ $application->testResults?->visit_purpose }}</td>
                @endif
                @if ($app_type_id == '6')
                    <td>{{ $application->bed_capacity }}</td>
                @endif
                <td>{{ $application->testResults?->overall_score }}</td>
                <td>{{ $application->testResults?->critical_score }}</td>
                <td>{{ $application->testResults?->comments }}</td>
                <td>{{ $application->testResults?->test_date }}</td>
                <td class="text-nowrap">
                    @if (isset($module))
                        @if (empty($application->testResults))
                            <a class="btn btn-primary btn-sm mx-2"
                                href="/test-results/food-establishments/create/{{ $application->id }}">
                                Add
                            </a>
                        @endif
                        @if (!empty($application->signOff))
                            <a href="/food-establishments/renewal/{{ $application->id }}"
                                class="btn btn-sm btn-success">Renew</a>
                        @endif
                    @endif
                    <a href="" class="btn btn-warning btn-sm">
                        Edit
                    </a>
                </td>
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
