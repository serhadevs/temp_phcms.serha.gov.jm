<table class="table table-striped no-wrap" id="summary_report" style="width:100%">
    <thead>
        <tr>
            <th class="sorting_disabled">Application Type</th>
            <th>No. Applications Received (Total)</th>
            <th>No. of New Applications</th>
            <th>No. of Renewals</th>
            <th>No. of Sign Offs Done</th>
            <th>Highest Amount per Category</th>
            <th>Lowest Amount per Category</th>
            <th>No. of Training Sessions Held</th>
            <th>Total Payment Recieved</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Food Handler Permit</td>
            <td>{{ $foodHandlers[0] }}</td>
            <td>{{ $foodHandlers[1] }}</td>
            <td>{{ $foodHandlers[2] }}</td>
            <td>{{ $foodHandlers[3] }}</td>
            <td>{{ $foodHandlers[4] }}</td>
            <td>{{ $foodHandlers[5] }}</td>
            <td>{{ $foodHandlers[6] }}</td>
            <td>{{ $foodHandlers[7] }}</td>
        </tr>
        <tr>
            <td>Barber/Cosmet Etc.</td>
            <td>{{ $barberCosmet[0] }}</td>
            <td>{{ $barberCosmet[1] }}</td>
            <td>{{ $barberCosmet[2] }}</td>
            <td>{{ $barberCosmet[3] }}</td>
            <td>{{ $barberCosmet[4] }}</td>
            <td>{{ $barberCosmet[5] }}</td>
            <td>{{ $barberCosmet[6] }}</td>
            <td>{{ $barberCosmet[7] }}</td>
        </tr>
        <tr>
            <td>Food Establishments</td>
            <td>{{ $foodEstablishments[0] }}</td>
            <td>{{ $foodEstablishments[1] }}</td>
            <td>{{ $foodEstablishments[2] }}</td>
            <td>{{ $foodEstablishments[3] }}</td>
            <td>{{ $foodEstablishments[4] }}</td>
            <td>{{ $foodEstablishments[5] }}</td>
            <td>{{ $foodEstablishments[6] }}</td>
            <td>{{ $foodEstablishments[7] }}</td>
        </tr>
        <tr>
            <td>Swimming Pools</td>
            <td>{{ $swimmingPools[0] }}</td>
            <td>{{ $swimmingPools[1] }}</td>
            <td>{{ $swimmingPools[2] }}</td>
            <td>{{ $swimmingPools[3] }}</td>
            <td>{{ $swimmingPools[4] }}</td>
            <td>{{ $swimmingPools[5] }}</td>
            <td>{{ $swimmingPools[6] }}</td>
            <td>{{ $swimmingPools[7] }}</td>
        </tr>
        <tr>
            <td>Tourist Establishments</td>
            <td>{{ $touristEstablishments[0] }}</td>
            <td>{{ $touristEstablishments[1] }}</td>
            <td>{{ $touristEstablishments[2] }}</td>
            <td>{{ $touristEstablishments[3] }}</td>
            <td>{{ $touristEstablishments[4] }}</td>
            <td>{{ $touristEstablishments[5] }}</td>
            <td>{{ $touristEstablishments[6] }}</td>
            <td>{{ $touristEstablishments[7] }}</td>
        </tr>
        <tr>
            <td>Foodhandler Clinics</td>
            <td>{{ $foodClinics[0] }}</td>
            <td>{{ $foodClinics[1] }}</td>
            <td>{{ $foodClinics[2] }}</td>
            <td>{{ $foodClinics[3] }}</td>
            <td>{{ $foodClinics[4] }}</td>
            <td>{{ $foodClinics[5] }}</td>
            <td>{{ $foodClinics[6] }}</td>
            <td>{{ $foodClinics[7] }}</td>
        </tr>
        <tr>
            <td class="fw-bold">Total</td>
            <td>{{ $foodHandlers[0] + $barberCosmet[0] + $foodEstablishments[0] + $swimmingPools[0] + $touristEstablishments[0] + $foodClinics[0] }}
            </td>
            <td>{{ $foodHandlers[1] + $barberCosmet[1] + $foodEstablishments[1] + $swimmingPools[1] + $touristEstablishments[1] + $foodClinics[1] }}
            </td>
            <td>{{ $foodHandlers[2] + $barberCosmet[2] + $foodEstablishments[2] + $swimmingPools[2] + $touristEstablishments[2] + $foodClinics[2] }}
            </td>
            <td>{{ $foodHandlers[3] + $barberCosmet[3] + $foodEstablishments[3] + $swimmingPools[3] + $touristEstablishments[3] + $foodClinics[3] }}
            </td>
            <td colspan=""></td>
            <td colspan=""></td>
            <td colspan=""></td>
            <td>{{ $foodHandlers[7] + $barberCosmet[7] + $foodEstablishments[7] + $swimmingPools[7] + $touristEstablishments[7] + $foodClinics[7] }}
            </td>
        </tr>
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
    new DataTable('#summary_report', {
        scrollX: true,
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
