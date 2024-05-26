<table class="table table-striped no-warp" id="processed_health_interviews" style="width:100%">
    <thead>
        <tr>
            <th>App#</th>
            <th class="text-nowrap">Application Type</th>
            <th class="text-nowrap">First Name</th>
            <th class="text-nowrap">Middle Name</th>
            <th class="text-nowrap">Last Name</th>
            <th>Literacy</th>
            <th>Thypoid</th>
            <th class="text-nowrap">Lived Abroad</th>
            <th class="text-nowrap">Travelled Abroad</th>
            <th class="text-nowrap">Hands Condition</th>
            <th class="text-nowrap">Finger Condition</th>
            <th class="text-nowrap">Teeth Condition</th>
            <th class="text-nowrap">Added By</th>
            <th class="text-nowrap">Apt. Date</th>
            <th class="text-nowrap">Apt. Time</th>
            <th class="text-nowrap">Apt. Venue</th>
            <th class="text-nowrap">Sign off Status</th>
            <th>Option</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($health_interviews as $interview)
            <tr>
                <td>{{ empty($interview->healthCertApplication) ? $interview->permitApplication?->id : $interview->healthCertApplication?->id }}
                </td>
                <td>{{ empty($interview->healthCertApplication) ? 'Food Handlers Permit' : 'Health Certificate' }}
                </td>
                <td>{{ empty($interview->healthCertApplication) ? $interview->permitApplication?->firstname : $interview->healthCertApplication?->firstname }}
                </td>
                <td>{{ empty($interview->healthCertApplication) ? $interview->permitApplication?->middlename : $interview->healthCertApplication?->middlename }}
                </td>
                <td>{{ empty($interview->healthCertApplication) ? $interview->permitApplication?->lastname : $interview->healthCertApplication?->lastname }}
                </td>
                <td>
                    @if ($interview->literate)
                        <span class="badge bg-success">YES</span>
                    @endif
                    @if (!$interview->literate)
                        <span class="badge bg-danger">NO</span>
                    @endif
                </td>
                <td>
                    @if ($interview->typhoid)
                        <span class="badge bg-success">YES</span>
                    @endif
                    @if (!$interview->typhoid)
                        <span class="badge bg-danger">NO</span>
                    @endif
                </td>
                <td>
                    @if ($interview->lived_abroad)
                        <span class="badge bg-success">YES</span>
                    @endif
                    @if (!$interview->lived_abroad)
                        <span class="badge bg-danger">NO</span>
                    @endif
                </td>
                <td>
                    @if ($interview->travel_abroad)
                        <span class="badge bg-success">YES</span>
                    @endif
                    @if (!$interview->travel_abroad)
                        <span class="badge bg-danger">NO</span>
                    @endif
                </td>
                <td>{{ strtoupper($interview->hands_condition) }}</td>
                <td>{{ strtoupper($interview->fingernails_condition) }}</td>
                <td>{{ strtoupper($interview->teeth_condition) }}</td>
                <td>{{ strtoupper($interview?->user?->firstname[0] . '.' . $interview?->user?->lastname) }}
                </td>
                <td>
                    {{ $interview->permit_application_id == ''
                        ? $interview->healthCertApplication?->appointment[0]?->appointment_date
                        : ($interview->permitApplication?->establishment_clinic_id == ''
                            ? $interview->permitApplication?->appointment[0]?->appointment_date
                            : $interview->permitApplication?->establishmentClinics?->proposed_date) }}
                </td>
                <td>
                    {{ $interview->permit_application_id == ''
                        ? strtoupper($interview->healthCertApplication?->appointment[0]?->examDate?->exam_day) .
                            '-' .
                            $interview->healthCertApplication?->appointment[0]?->examDate?->exam_start_time
                        : ($interview->permitApplication?->establishment_clinic_id == ''
                            ? strtoupper($interview->permitApplication?->appointment[0]?->examDate?->exam_day) .
                                '-' .
                                $interview->permitApplication?->appointment[0]?->examDate?->exam_start_time
                            : $interview->permitApplication?->establishmentClinics?->proposed_time) }}
                </td>
                <td>
                    {{ $interview->permit_application_id == ''
                        ? strtoupper($interview->healthCertApplication?->appointment[0]?->examDate?->examSites?->name)
                        : ($interview->permitApplication?->establishment_clinic_id == ''
                            ? strtoupper($interview->permitApplication?->appointment[0]?->examDate?->examSites?->name)
                            : $interview->permitApplication?->establishmentClinics?->address) }}
                </td>
                <td>
                    @if ($interview->sign_off_status)
                        <span class="badge bg-success">Approved</span>
                    @endif
                    @if (!$interview->sign_off_status)
                        <span class="badge bg-danger">Unapproved</span>
                    @endif
                </td>
                <td class="text-nowrap">
                    <a class="btn btn-sm btn-primary" href="/health-interview/view/{{ $interview->id }}">View</a>
                    @if ($interview->sign_off_status != '1')
                        <a class="btn btn-warning btn-sm" href="/health-interview/edit/{{ $interview->id }}">Edit</a>
                        <button class="btn-danger btn btn-sm"
                            onclick="removeEntry('/health-interview', {{ json_encode($interview->id) }})">Delete</button>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@include('partials.messages.remove_entry_message')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<script>
    new DataTable('#processed_health_interviews', {
        scrollX: true,
        initComplete: function() {
            loading.close()
        }
        // responsive: true
    });
</script>
