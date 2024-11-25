<table class="table table-bordered table-striped nowrap" id="appointments" style="width:100%">
    <thead>
        <tr>
            <th>App #</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Permit Category</th>
            <th>Exam Time</th>
            <th>Exam Site</th>
            <th>Present</th>
            <th>Signature</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($appointments as $appointment)
            <tr>
                <td>
                    <a href="{{ route('permit.application.view', ['id' => $appointment->permit_application_id]) }}">
                        View Application
                    </a>
                    </td>
                <td>{{ $appointment?->applications?->firstname }}</td>
                <td>{{ $appointment?->applications?->lastname }}</td>
                <td>{{ $appointment?->examDate?->permitCategory->name ?? 'N/A'}}</td>
                <td>{{ $appointment?->examDate?->exam_start_time }}</td>
                <td>{{ $appointment?->examSitesId?->name ?? 'N/A' }}</td>
                <td></td>
                <td></td>
                {{-- <td>{{ $appointment?->applications-> }}</td> --}}
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
    new DataTable('#appointments', {
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

  
