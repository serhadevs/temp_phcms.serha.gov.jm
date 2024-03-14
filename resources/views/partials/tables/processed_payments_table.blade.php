<table class="table table-striped no-warp" id="payment_info" style="width:100%">
    <thead>
        <tr>
            <th></th>
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
                <td>
                    @if ($payment_data->cancellation_id != '')
                        @if ($payment_data->cancellation_approved_status == '2')
                            <i class="bi bi-flag-fill text-danger"></i>
                        @endif
                        @if (!$payment_data->cancellation_approved_status)
                            <i class="bi bi-flag text-danger"></i>
                        @endif
                    @endif
                </td>
                <td>{{ $payment_data->name }}</td>
                <td>{{ $payment_data->application_id }}</td>
                <td>{{ $payment_data->receipt_no }}</td>
                <td>{{ $payment_data->total_cost }}</td>
                <td>{{ $payment_data->amount_paid }}</td>
                <td>{{ $payment_data->change_amt }}</td>
                <td>{{ \Carbon\Carbon::parse($payment_data->created_at)->format('d M Y') }}</td>
                {{-- Insert Here --}}
                <td></td>
                <td>
                    <button class="btn btn-danger btn-sm"
                        onclick="sendCancelRequest({{ json_encode($payment_data->payment_id) }})">Request Cancel</button>
                    <a class="btn btn-primary btn-sm"
                        href="/payments/receipt/print/{{ $payment_data->payment_id }}">Reprint Receipt</a>
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
        scrollX: true,
        "columnDefs": [{
            "width": "20%",
            "targets": 9
        }],
        responsive: true
    });

    async function sendCancelRequest(payment_id) {
        swal.fire({
                title: "Enter reason \n for payment cancellation.",
                icon: "warning",
                input: "text",
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: `Yes, I am sure!`,
                cancelButtonText: `No, Cancel it!`
            })
            .then(result => {
                if (result.isConfirmed) {
                    if (result.isConfirmed) {
                        console.log(result.value);
                        $.post({!! json_encode(url('/payments/cancellations/request')) !!}, {
                            _method: "POST",
                            data: {
                                payment_id: payment_id,
                                reason: result.value
                            },
                            _token: "{{ csrf_token() }}"
                        }).then(function(data) {
                            console.log(data);
                            if (data == "success") {
                                swal.fire(
                                    "Done!",
                                    "Payment Cancellation has been successfully Requested.",
                                    "success").then(esc => {
                                    if (esc) {
                                        location.reload();
                                    }
                                });
                            } else {
                                swal.fire(
                                    "Oops! Something went wrong.",
                                    data,
                                    "error");
                            }
                        })
                    }
                }
            })
    }
</script>
