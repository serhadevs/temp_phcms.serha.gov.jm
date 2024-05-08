<table class="table table-striped no-warp" id="food_clinics" style="width:100%">
    <thead>
        <tr>
            <th>Options</th>
            <th class="text-nowrap">App #</th>
            <th class="text-nowrap">First Name</th>
            <th class="text-nowrap">Middle Name</th>
            <th class="text-nowrap">Last Name</th>
            <th class="text-nowrap">Literacy</th>
            <th class="text-nowrap">Thyroid</th>
            <th class="text-nowrap">Lived Abroad</th>
            <th class="text-nowrap">Travelled Abroad</th>
            <th class="text-nowrap">Hands Condition</th>
            <th class="text-nowrap">Fingers Condition</th>
            <th class="text-nowrap">Teeth Condition</th>
            <th class="text-nowrap">Symptoms</th>
            <th class="text-nowrap">Sign Off Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applications as $application)
            <tr>
                <td class="text-nowrap">
                    @if (empty($application->healthInterviews))
                        <a href="/health-interview/create/{{ $app_type_id }}/{{ $application->id }}"
                            class="btn btn-sm btn-primary mx-1">Add</a>
                    @endif
                    <a href="" class="btn-warning btn btn-sm">Edit</a>
                </td>
                <td>{{ $application->id }}</td>
                <td>{{ $application->firstname }}</td>
                <td>{{ $application->middlename }}</td>
                <td>{{ $application->lastname }}</td>
                <td>
                    @if ($application->healthInterviews?->literate == '1')
                        <span class="badge bg-success">YES</span>
                    @elseif($application->healthInterviews?->literate == '0')
                        <span class="badge bg-danger">NO</span>
                    @endif
                </td>
                <td>
                    @if ($application->healthInterviews?->typhoid == '1')
                        <span class="badge bg-success">YES</span>
                    @elseif($application->healthInterviews?->typhoid == '0')
                        <span class="badge bg-danger">NO</span>
                    @endif
                </td>
                <td>
                    @if ($application->healthInterviews?->lived_abroad == '1')
                        <span class="badge bg-success">YES</span>
                    @elseif($application->healthInterviews?->lived_abroad == '0')
                        <span class="badge bg-danger">No</span>
                    @endif
                </td>
                <td>
                    @if ($application->healthInterviews?->travel_abroad == '1')
                        <span class="badge bg-success">YES</span>
                    @elseif($application->healthInterviews?->travel_abroad == '0')
                        <span class="badge bg-danger">NO</span>
                    @endif
                </td>
                <td>{{ strtoupper($application->healthInterviews?->hands_condition) }}</td>
                <td>{{ strtoupper($application->healthInterviews?->fingernails_condition) }}</td>
                <td>{{ strtoupper($application->healthInterviews?->teeth_condition) }}</td>
                <td>
                    @if (empty($application->healthInterviews->healthInterviewSymptom))
                        <span class="badge bg-danger">N/A</span>
                    @elseif(!empty($application->healthInterviews->healthInterviewSymptom))
                        @foreach ($application->healthInterviews->healthInterviewSymptom as $symp)
                            {{ $symp->symptoms?->name . ',' }}
                        @endforeach
                    @endif
                </td>
                <td>
                    @if ($application->healthInterviews?->sign_off_status)
                        <span class="badge bg-success">Approved</span>
                    @elseif(!$application->healthInterviews?->sign_off_status)
                        <span class="badge bg-danger">Unapproved</span>
                    @endif
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
    new DataTable('#food_clinics', {
        scrollX: true,
        initComplete: function() {
            loading.close()
        }
        // responsive: true
    })
</script>
