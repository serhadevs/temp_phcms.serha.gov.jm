<table class="table table-striped table-bordered" id="printed_cards_report_table" style="width:100%">
    <thead>
        <tr>
            <th class="text-nowrap">Application ID</th>
            <th class="text-nowrap">Application Type</th>
            @if ($app_type_id == 1)
                <th class="text-nowrap">First Name</th>
                <th class="text-nowrap">Last Name</th>
            @endif
            <th class="text-nowrap">Establishment Name</th>
            <th class="text-nowrap">Permit No</th>
            <th class="text-nowrap">Category</th>
            <th class="text-nowrap">Test Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($printed_cards as $card)
            <tr>
                <td>{{ $card->id }}</td>
                <td>{{ $app_type_id == 1 ? 'Food Handlers Permit' : 'Food Establishment' }}</td>
                @if ($app_type_id == 1)
                    <td>{{ strtoupper($card->firstname) }}</td>
                    <td>{{ strtoupper($card->lastname) }}</td>
                @endif
                <td>
                    @if ($app_type_id == 1)
                        {{ !empty($card->establishmentClinics) ? $card->establishmentClinics?->name : 'N/A' }}
                    @elseif($app_type_id == 3)
                        {{ $card->establishment_name }}
                    @endif
                </td>
                <td>
                    {{ $card->permit_no }}
                </td>
                <td>
                    @if ($app_type_id == 1)
                        {{ $card->permitCategory?->name }}
                    @else
                        {{ $card?->establishmentCategory?->name }}
                    @endif
                </td>
                <td>
                    {{ $card?->testResults?->test_date }}
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

<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.13.7/api/sum().js"></script>

<script>
    new DataTable('#printed_cards_report_table', {
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        scrollX: true,
        initComplete: function() {
            loading.close()
        },
        responsive: true
    })
</script>

<script>
    window.onload = () => {
        buttons = document.querySelectorAll("div.dt-buttons button");
        buttons.forEach((element) => {
            element.classList.add("btn");
            element.classList.add("btn-secondary")
        })
    }
</script>
<style>
    div.dt-buttons {
        width: 50%;
        float: left;
    }

    .dataTables_info {
        width: 50%;
        float: left;
    }
</style>
