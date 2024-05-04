<table id="barber_cosmet_table" class="table table-striped" style="width:100%;max-width:100%">
    <thead>
        <tr>
            @if (isset($is_result))
                <th></th>
            @endif
            <th>App. #</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Address</th>
            <th>Permit Number</th>
            <th>Payment Status</th>
            <th>Sex</th>
            <th>Sign Off Status</th>
            <th>Appointment Date & Time</th>
            <th>Application Date</th>
            <th>Exam Site</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applications as $application)
            <tr>
                @if (isset($is_result))
                    <td>
                        <a href="/test-results/barber-cosmet/create/{{ $application->id }}"
                            class="btn btn-primary btn-sm mx-1">Select</a>
                    </td>
                @endif
                <td>{{ $application->id }}</td>
                <td>{{ strtoupper($application->firstname) }}</td>
                <td>{{ strtoupper($application->lastname) }}</td>
                <td>{{ strtoupper($application->address) }}</td>
                <td>{{ $application->permit_no }}</td>
                <td>
                    <span class="badge text-bg-{{ !empty($application->payment) ? 'success' : 'danger' }}">
                        {{ !empty($application->payment) == '1' ? 'PAID' : 'NOT PAID' }}
                </td>
                <td>{{ strtoupper($application->sex) }}</td>
                <td>
                    <span class="badge text-bg-{{ $application->sign_off_status == '1' ? 'success' : 'danger' }}">
                        {{ $application->sign_off_status == '1' ? 'APPROVED' : 'NOT APPROVED' }}
                    </span>
                </td>
                <td>
                    {{ $application?->appointment->first()?->appointment_date . '-' . $application?->appointment->first()?->examDate?->exam_start_time }}
                </td>
                <td>
                    {{ $application->application_date }}
                </td>
                <td>
                    {{ strtoupper($application->appointment->first()?->examDate?->examSites?->name) }}
                </td>
                <td class="text-nowrap">
                    <a href="/barber-cosmet/view/{{ $application->id }}" class="btn-sm btn btn-primary mx-1">View</a>
                    @if (!isset($is_result))
                        <a href="/barber-cosmet/edit/{{ $application->id }}"
                            class="btn btn-warning btn-sm mx-1">Edit</a>
                        @if ($application->sign_off_status == '1')
                            <a href="/barber-cosmet/renewal/{{ $application->id }}"
                                class="btn-success btn-sm btn mx-1">Renew</a>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    new DataTable('#barber_cosmet_table', {
        // responsive: true,
        scrollX: true,
    });
</script>
