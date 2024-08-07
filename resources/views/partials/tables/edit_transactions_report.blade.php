<table class="table table-striped table-bordered" id="transaction_report_table" style="width:100%">
    <thead>
        <tr>
            <th class="text-nowrap">Entry ID</th>
            <th class="text-nowrap">Application Type</th>
            <th class="text-nowrap">Edit Type</th>
            <th>Editor</th>
            <th class="text-nowrap">Reason for Edit</th>
            <th class="text-nowrap">Fields Changed</th>
            <th>Old Values</th>
            <th>New Value</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($transactions as $transaction)
            <tr>
                <td>{{ $transaction->table_id }}</td>
                <td>{{ $transaction->applicationType?->name . ' - ' . $transaction->systemOperationType?->name }}</td>
                <td>{{ strtoupper($transaction->editType?->name) }}</td>
                <td>{{ strtoupper($transaction->user?->firstname) . ' ' . strtoupper($transaction->user?->lastname) }}
                </td>
                <td>
                    {{ $transaction->reason }}
                </td>
                <td>
                    @foreach ($transaction->changedColumns as $column)
                        {{ strtoupper(str_replace('_', ' ', str_replace('_id', '', $column->column_name))) }}
                        <br />
                    @endforeach
                </td>
                <td>
                    @foreach ($transaction->changedColumns as $column)
                        {{ strtoupper($column->old_value ? $column->old_value : 'N/A') }} <br />
                    @endforeach
                </td>
                <td>
                    @foreach ($transaction->changedColumns as $column)
                        {{ strtoupper($column->new_value ? $column->new_value : 'N/A') }} <br />
                    @endforeach
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
    new DataTable('#transaction_report_table', {
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
