<table class="table table-striped no-warp" id="outstanding_results" style="width:100%">
    <thead>
        <tr>
            <th>App No.</th>
            <th>Name</th>
            @if ($app_type_id == '3')
                <th>Category</th>
            @endif
            <th>Address</th>
            <th>Permit No.</th>
            <th class="text-nowrap">App Date</th>
            <th>TRN</th>
            @if ($app_type_id == '3')
                <th>Operators</th>
            @endif
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
                <td>{{ $application->permit_no }}</td>
                <td>{{ $application->application_date }}</td>
                <td>{{ $application->trn }}</td>
                @if ($app_type_id == '3')
                    <td>
                        @foreach ($application->operators as $operator)
                            {{ $operator->name_of_operator . ' ' }}
                        @endforeach
                    </td>
                @endif
                <td>
                    <a href="{{ $app_type_id == '3' ? '/test-results/food-establishments/create/' . $application->id : '' }}"
                        class="btn btn-primary btn-sm">Select</a>
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
        initComplete: function() {
            loading.close()
        }
        // responsive: true
    });
</script>
