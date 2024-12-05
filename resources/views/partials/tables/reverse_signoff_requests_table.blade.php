<table class="table table-bordered table-striped" id="reverse_signoff_table" style="width:100%">
    <thead>
        <tr>
            <th>Application ID</th>
            <th>Application Type</th>
            <th>Requestor</th>
            <th>Date Requested</th>
            <th>Reason for reversal</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($requests as $request)
            <tr>
                <td>{{ $request->signOffs?->application_id }}</td>
                <td>{{ $request->application_type_id == 1 ? "Food Handler's Permit" : 'Food Establishment' }}</td>
                <td>{{ $request->user?->firstname . ' ' . $request->user?->lastname }}</td>
                <td>{{ $request->created_at }}</td>
                <td>{{ $request->reason }}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="approveReversal({{ json_encode($request->id) }})">
                        Approve Reversal
                    </button>
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
    new DataTable('#reverse_signoff_table', {
        scrollX: true,
        initComplete: function() {
            loading.close()
        },
        responsive: true
    });
</script>

<script>
    function approveReversal(id) {
        swal.fire({
            title: "Are you sure you want to approve this request?",
            text: "Sign Off Reversal is permanent",
            icon: "warning",
            showCancelButton: true,
            showConfirmButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.post({!! json_encode(url('/sign-off/reversal/approve')) !!} + "/" +
                    id, {
                        _method: "GET",
                        _token: "{{ csrf_token() }}"
                    }).then(function(data) {
                    if (data[0] == "success") {
                        swal.fire(
                            "Done!",
                            data[1],
                            "success").then(
                            esc => {
                                if (esc) {
                                    location
                                        .reload();
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
        })
    }
</script>
