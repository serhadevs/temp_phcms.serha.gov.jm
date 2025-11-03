<table class="table table-striped nowrap table-bordered" id="collectedCards" style="width:100%">
    <thead>
        <tr>
            <th>App Number</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Collected By</th>
            <th>Date Collected</th>
            <th>ID Presented</th>
         
        </tr>
    </thead>
    <tbody>
        @foreach ($collected_cards as $item)
            <tr>
                <td>{{ $item->app_id}}</td>
                <td>{{ $item->permit_application?->firstname}}</td>
                <td>{{ $item->permit_application?->lastname}}</td>
                <td>{{ $item->collected_by }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F d, Y h:i A') }}</td>
                <td>{{ $item->identificationType?->name }}</td>
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
    new DataTable('#collectedCards', {
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
