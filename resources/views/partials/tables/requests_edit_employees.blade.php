<table class="table table-striped" id="requests_edit_table" style="width:100%">
    <thead>
        <tr>
            <th>Aplication ID</th>
            <th>Establishment Name</th>
            <th>Establishment Address</th>
            <th>Number of Increment</th>
            <th>Requestor</th>
            <th>Reason for addition</th>
            <th>Option</th>
    </thead>
    <tbody>
        @foreach ($requests as $transaction)
            <tr>
                <td>{{ $transaction->table_id }}</td>
                <td>{{ $transaction->establishmentClinic?->name }}</td>
                <td>{{ $transaction->establishmentClinic?->address }}</td>
                <td>{{ $transaction->changedColumns?->first()?->new_value - $transaction->changedColumns?->first()?->old_value }}
                </td>
                <td>{{ $transaction->user?->firstname }} {{ $transaction->user?->lastname }}</td>
                <td>{{ $transaction->reason }}</td>
                <td>
                    <button class="btn btn-sm btn-primary" type="button"
                        onclick="approve({{ json_encode($transaction->id) }})">
                        Approve
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    function approve(edit_transaction_id) {
        swal.fire({
            title: "Are you sure you want to approve this edit?",
            text: "Ensure correct request was selected",
            icon: 'question',
            showConfirmButton: true,
            showCancelButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.post({!! json_encode(url('/food-handlers-clinics/request/employees/approve')) !!} + "/" + edit_transaction_id, {
                    _method: "PUT",
                    _token: "{{ csrf_token() }}"
                }).then((data) => {
                    if (data[0] == 'success') {
                        swal.fire({
                            title: "Done",
                            text: data[1],
                            icon: 'success'
                        }).then((esc) => {
                            if (esc) {
                                location.reload();
                            }
                        })
                    } else {
                        swal.fire({
                            title: "Error",
                            text: data,
                            icon: 'error'
                        })
                    }
                })
            }
        })
    }
</script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<script>
    new DataTable('#requests_edit_table', {
        scrollX: true,
    });
</script>
