<table class="table table-striped no-warp" id="outstanding_results" style="width:100%">
    <thead>
        <tr>
            <th>Options</th>
            <th>App #</th>
            @if ($app_type_id == '1')
                <th>Permit Category</th>
            @endif
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Test Date</th>
            <th>Test Location</th>
            <th>Gender</th>
            <th>Date of Birth</th>
            <th>Payment Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($applications as $application)
            <tr>
                <td><a href="/health-interview/create/{{ $app_type_id }}/{{ $application->id }}"
                        class="btn btn-primary btn-sm">Select</a></td>
                <td>{{ $application->id }}</td>
                @if ($app_type_id == '1')
                    <td>
                        {{ $application->permitCategory->name }}
                    </td>
                @endif
                <td>{{ $application->firstname }}</td>
                <td>{{ $application->middlename }}</td>
                <td>{{ $application->lastname }}</td>
                <td>
                    @if ($app_type_id == '1')
                        {{ $application->establishment_clinic_id == '' ? $application?->appointment->first()?->appointment_date : $application->establishmentClinic?->proposed_date }}
                    @endif
                    @if ($app_type_id == '2')
                        {{ $application?->appointment->first()?->appointment_date }}
                    @endif
                </td>
                <td>
                    @if ($app_type_id == '1')
                        {{ $application->establishment_clinic_id == '' ? $application?->appointment->first()?->examDate?->examSites?->name : $application->establishmentClinic?->proposed_date }}
                    @endif
                    @if ($app_type_id == '2')
                        {{ $application?->appointment->first()?->examDate->examSites?->name }}
                    @endif
                </td>
                <td>{{ $application->gender }}</td>
                <td>{{ $application->date_of_birth }}</td>
                <td>{{ $application->payment?->created_at }}</td>
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
    })
</script>
