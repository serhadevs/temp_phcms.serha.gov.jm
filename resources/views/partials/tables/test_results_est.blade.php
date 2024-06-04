<table class="table table-striped no-warp table-bordered" id="outstanding_results" style="width:100%">
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
                <th>Zone</th>
            @endif
            @if ($app_type_id == '6')
                <th>Bed Capacity</th>
            @endif
            <th>Inspector</th>
            <th>Inspection Date</th>
            <th>Critical Score</th>
            <th>Overall Score</th>
            <th>Sign Off Status</th>
            @if ($app_type_id == '3')
                <th>Operators</th>
            @endif
            <th>Comments</th>
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
                    <td>{{ strtoupper($application->testResults?->visit_purpose) }}</td>
                    <td>{{ strtoupper($application->zone) }}</td>
                @endif
                @if ($app_type_id == '6')
                    <td>{{ $application->bed_capacity }}</td>
                @endif
                <td>{{ $application->testResults?->staff_contact }}</td>
                <td>{{ \Carbon\Carbon::parse($application->testResults?->test_date)->format('M-j-Y') }}</td>
                <td>{{ $application->testResults?->critical_score }}</td>
                <td>{{ $application->testResults?->overall_score }}</td>
                <td><span
                        class="badge text-bg-{{ $application->sign_off_stauts == '1' ? 'success' : 'danger' }}">{{ $application->sign_off_stauts == '1' ? 'COMPLETE' : 'INCOMPLETE' }}</span>
                </td>
                @if ($app_type_id == '3')
                    <td>
                        @foreach ($application->operators as $operator)
                            {{ strtoupper($operator?->name_of_operator) }}
                        @endforeach
                    </td>
                @endif
                <td>{{ $application->testResults?->comments }}</td>
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
                    @if ($application->sign_off_status != '1')
                        <a href="/test-results/{{ $app_type_id == '3' ? 'food-establishments/edit' : ($app_type_id == '6' ? 'tourist-establishments/edit' : ($app_type_id == '5' ? 'swimming-pools/edit' : '')) }}/{{ $application->id }}"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>
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
@if (isset($is_general_report))
    {{-- Button Links --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.7/api/sum().js"></script>
    <script>
        new DataTable('#outstanding_results', {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            scrollX: true,
            initComplete: function() {
                loading.close()
            }
        });
    </script>
@else
    <script>
        new DataTable('#outstanding_results', {
            scrollX: true,
            responsive: true,
            initComplete: function() {
                loading.close()
            }
        });
    </script>
@endif
