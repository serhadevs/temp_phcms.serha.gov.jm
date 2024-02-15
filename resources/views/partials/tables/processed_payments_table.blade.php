<table class="table table-striped no-warp" id="payment_info" style="width:100%">
    <thead>
        <tr>
            <th>
                Application Type
            </th>
            <th>
                Application No.
            </th>
            <th>
                Receipt No.
            </th>
            <th>
                Total Cost
            </th>
            <th>
                Amount Paid
            </th>
            <th>
                Change
            </th>
            <th>
                Payment Date
            </th>
            <th>
                Paid By
            </th>
            <th>
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach (json_decode($payments_info) as $payment_data)
            <tr>
                <td>{{ $payment_data->name}}</td>
                <td>{{ $payment_data->application_id }}</td>
                <td>{{ $payment_data->receipt_no }}</td>
                <td>{{ $payment_data->total_cost }}</td>
                <td>{{ $payment_data->amount_paid }}</td>
                <td>{{ $payment_data->change_amt }}</td>
                <td>{{ \Carbon\Carbon::parse($payment_data->created_at)->format('d M Y') }}</td>
                <td></td>
                <td>
                   {{-- <a href="" class="btn btn-danger mx-1 btn-sm">Cancel</a> --}}
                   <a href="/payments/cancel/{{ $payment_data->payment_id }}" class="btn btn-danger btn-sm">Cancel Payment</a>
                   <a class="btn btn-primary btn-sm" href="/payments/receipt/print/{{ $payment_data->payment_id }}">Reprint Receipt</a>
                </td>
            </tr>
        @endforeach

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
    new DataTable('#payment_info', {
        scrollX:true,
        "columnDefs": [{
            "width": "20%",
            "targets": 8
        }],
        // responsive: true;
    });
</script>
