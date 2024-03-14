<table class="table table-striped no-warp" id="outstanding_payment_cancellations" style="width:100%">
    <thead>
        <tr>
            <th>Cancellation #</th>
            <th>Application #</th>
            <th>Application Type</th>
            <th>Payment Id</th>
            <th>Requested By</th>
            <th>Reason</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($paymentCancellations as $cancellation)
            <tr>
                <td>{{ $cancellation->id }}</td>
                <td>{{ $cancellation->payment?->application_id }}</td>
                <td>{{ $cancellation->payment?->applicationType->name }}</td>
                <td>{{ $cancellation->payment?->id }}</td>
                <td>{{ $cancellation->requester->firstname }} {{ $cancellation->requester->lastname }}</td>
                <td>{{ $cancellation->reason }}</td>
                <td>
                    <button class="btn btn-sm btn-success"
                        onclick="sendApproval({{ json_encode($cancellation->id) }}, 1)">Approve</button>
                    <button class="btn btn-sm btn-danger"
                        onclick="sendApproval({{ json_encode($cancellation->id) }}, 2)">Reject</button>
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
    new DataTable('#outstanding_payment_cancellations', {
        // scrollX: true,
        responsive: true
    });

    function sendApproval(cancellation_id, approval_stat) {
        swal.fire({
                title: "Are you sure you want to process cancellation?",
                text: "Tip: Ensure you review all elements before approval.",
                icon: "warning",
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: `Yes, I am sure!`,
                cancelButtonText: `No, Cancel it!`
            })
            .then(result => {
                if (result.isConfirmed) {
                    if (result.isConfirmed) {
                        $.post({!! json_encode(url('/payments/cancellations/approve')) !!}, {
                            _method: "POST",
                            data: {
                                cancellation_id: cancellation_id,
                                approval_stat: approval_stat
                            },
                            _token: "{{ csrf_token() }}"
                        }).then(function(data) {
                            console.log(data);
                            if (data == "success") {
                                swal.fire(
                                    "Done!",
                                    "Payment Cancellation has been processed successfully.",
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
