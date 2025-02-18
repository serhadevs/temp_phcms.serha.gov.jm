<table class="table table-bordered table-striped nowrap table-sm" id="estbyzone" style="width:100%">
    <thead>
        <tr>

            <th>Zone</th>
            <th>Est Name</th>
            <th>Operators</th>
            <th>Est Address</th>
            <th>Category</th>
            <th>Permit #</th>
            <th>Inspection Date</th>
            <th>Expiry Date</th>
            <th>Sat/UnSat</th>
            <th>Sign Off</th>



        </tr>
    </thead>
    <tbody>
        @foreach ($establishments as $item)
            <tr>
                <td><span class="badge text-bg-dark">{{ $item->zone }}</span></td>
                <td>{{ $item->establishment_name }}</td>
                <td>
                    @foreach ($item->operators as $operator)
                        <span class="badge text-bg-dark">{{ $operator->name_of_operator }}</span>
                    @endforeach
                </td>
                <td>{{ $item->establishment_address }}</td>
                <td><span class="badge text-bg-dark">{{ $item->establishmentCategory?->name }}</span></td>
                <td>{{ $item->permit_no }}</td>
                <td>

                    @if (optional($item->testResults)->test_date)
                        {{ \Carbon\Carbon::parse(optional($item->testResults)->test_date)->format('d, F Y') }}
                    @else
                        No Inspection Date
                    @endif

                </td>
                <td>
                    @if (optional($item->signOff)->expiry_date)
                        {{ \Carbon\Carbon::parse(optional($item->signOff)->expiry_date)->format('d,F Y') }}
                    @else
                        No Expiry Date
                    @endif
                </td>

                <td> <span
                        class="badge text-bg-{{ $item->testResults?->critical_score >= 59 ? 'success' : 'danger' }}">{{ $item->testResults?->critical_score >= 59 ? 'Satisfactory' : 'Not Satisfactory' }}</span>
                </td>
                <td><span
                        class="badge text-bg-{{ $item->signOff?->is_granted == 1 ? 'success' : 'danger' }}">{{ $item->signOff?->is_granted == 1 ? 'Signed Off' : 'Not Signed Off' }}</span>
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
    new DataTable('#estbyzone', {
        scrollX: true,
        initComplete: function() {
            loading.close()
        },
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "order": [],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
                data;
        },
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": ["sorting_disabled"]
        }],
    });
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
