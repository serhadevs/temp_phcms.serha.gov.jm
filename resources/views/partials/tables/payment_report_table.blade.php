<table class="table table-striped no-wrap" id="payment_report" style="width:100%">
    <thead>
        <tr>
            <th>Application Type</th>{{-- THERE --}}
            <th>Application No.</th>{{-- THERE --}}
            <th>Receipt No.</th>{{-- THERE --}}
            <th>Total Cost</th> {{-- THERE --}}
            <th>Amount Paid</th> {{-- THERE --}}
            <th>Change</th>{{-- THERE --}}
            <th>Paymnent Date</th>{{-- THERE --}}
        </tr>
    </thead>
    <tbody>
        <?php
        $pass_app_number = '';
        ?>
        @foreach (json_decode($json_payments) as $json_payment)
            <tr>
                <td>{{ $json_payment?->app_type }}</td>
                <td>{{ $json_payment?->app_number }}</td>
                <td>{{ $json_payment?->receipt_no }}</td>
                <td>{{ $json_payment?->total_cost }}</td>
                <td>{{ $json_payment?->amount_paid }}</td>
                <td>{{ $json_payment?->change_amt }}</td>
                <td>{{ Carbon\Carbon::parse($json_payment->payment_date)->format('F j, Y, g:i a') }}</td>
            </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" style="text-align:right;font-weight: bold;"></th>
            <th style="font-weight: bold;"></th>
        </tr>
    </tfoot>
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
    new DataTable('#payment_report', {
        scrollX: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        "footerCallback": function(row, data, start, end, display) {
            var api = this.api(),
                data;

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };

            // Total over all pages
            total = api
                .column(3)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(3, {
                    page: 'current'
                })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(3).footer()).html(
                'Total : $' + pageTotal + ' ( $' + total + ' total)'
            );
        }
    });
</script>

<script>
    window.onload = () => {
        buttons = document.querySelectorAll("div.dt-buttons button");

        // alert("Testing")
        buttons.forEach((element) => {
            element.classList.add("btn");
            element.classList.add("btn-secondary")
        })
    }
</script>
