<table id="swimming_pool_table" class="table table-striped" style="width:100%;max-width:100%">
    <thead>
        <tr>
            @if (isset($is_results))
                <th>Option</th>
            @endif
            <th>App. No.</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Payment Status</th>
            <th>Sign Off Status</th>
            <th>Permit No</th>
            <th>Address</th>
            <th>Application Date</th>
            @if (!isset($is_results))
                <th>Options</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($applications as $application)
            <tr>
                @if (isset($is_results))
                    <td>
                        <a href="/test-results/swimming-pools/create/{{ $application->id }}"
                            class="btn btn-primary btn-sm">Select</a>
                    </td>
                @endif
                <td>{{ $application->id }}</td>
                <td>{{ $application->firstname }}</td>
                <td>{{ $application->middlename }}</td>
                <td>{{ $application->lastname }}</td>
                <td class="text-center">
                    <span class="badge text-bg-{{ !empty($application->payment) ? 'success' : 'danger' }}">
                        {{ !empty($application->payment) == '1' ? 'PAID' : 'NOT PAID' }}
                </td>
                <td>
                    <span class="badge text-bg-{{ $application->sign_off_status == '1' ? 'success' : 'danger' }}">
                        {{ $application->sign_off_status == '1' ? 'APPROVED' : 'NOT YET APPROVED' }}
                </td>
                <td>{{ $application->permit_no }}</td>
                <td>{{ $application->swimming_pool_address }}</td>
                <td>{{ $application->application_date }}</td>
                @if (!isset($is_results))
                    <td>
                        <a href="/swimming-pools/edit/{{ $application->id }}"
                            class="btn btn-sm btn-warning mx-1">Edit</a>
                        @if ($application->sign_off_status == '1')
                            <a href="/swimming-pools/renewal/{{ $application->id }}"
                                class="btn btn-success btn-sm">Renew</a>
                        @endif
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    new DataTable('#swimming_pool_table', {
        // responsive: true,
        scrollX: true,
        initComplete: function() {
            loading.close()
        }
    });
</script>
