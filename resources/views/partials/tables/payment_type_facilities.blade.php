<table class="table table-striped nowrap table-bordered" id="payment_type_facilities_table" style="width:100%">
    <thead>
        <tr>
            <th>
                Payment Type
            </th>
            <th>
                Facility Id
            </th>
            <th>
                Status
            </th>
            <th>
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($ptfs as $ptf)
            <tr>
                <td>
                    {{ $ptf->paymentType?->name }}
                </td>
                <td>
                    {{ $ptf->facility?->name }}
                </td>
                <td>
                    {{ $ptf->status == "0" ? 'Inactive' : 'Active' }}
                </td>
                <td>
                    <button class="btn btn btn-{{ $ptf->status == "0" ? 'success' : 'danger' }}"
                        onclick="updateStatus({{json_encode($ptf->payment_type_id)}} ,{{json_encode($ptf->facility_id)}})">
                        {{ $ptf->status == "0" ? 'Activate' : 'Inactivate' }}
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    function updateStatus(payment_type_id, facility_id) {

        swal.fire({
            title: 'Are you sure you want to change the active status of this payment method?',
            text: 'Ensure correct entry was selected.',
            icon: 'info',
            showCancelButton: true,
            showCancelButton: true
        }).then((result2) => {
            if (result2.isConfirmed) {
                $.get({!! json_encode(url('/')) !!} +"/admin/payment/type/facilities/" + payment_type_id + "/" +
                    facility_id, {
                        _method: "GET",
                        _token: "{{ csrf_token() }}"
                    }).then(function(data) {
                    console.log(data);
                    if (data[0] == "success") {
                        swal.fire(
                            "Done!",
                            data[1],
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
                });
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
    new DataTable('#payment_type_facilities_table', {
        scrollX: true,
    });
</script>
