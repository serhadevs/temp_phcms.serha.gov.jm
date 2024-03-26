<table class="table table-striped no-warp" id="outstanding_results" style="width:100%">
    <thead>
        <tr>
            <th>Facility</th>
            <th>Est. Name</th>
            <th>Est Address.</th>
            <th>Licence No.</th>
            <th>Inspection Date</th>
            <th>Inspected By</th>
            <th>Date Recived</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tourist_ests as $est)
            <tr>
                <td>{{ $est->payments->facility?->name }}</td>
                <td>{{ $est->establishment_name }}</td>
                <td>{{ $est->establishment_address }}</td>
                <td>{{ $est->permit_no }}</td>
                <td>{{ $est->testResults->test_date }}</td>
                <td>{{ $est->testResults->staff_contact }}</td>
                <td>{{ $est->created_at }}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="deletePrintable({{ json_encode($est->printableApplication?->id) }})">Delete</button>
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
    new DataTable('#outstanding_results', {
        scrollX: true,
        responsive: true
    });

    function deletePrintable(appId) {
        swal.fire({
            icon: 'warning',
            title: "Are you sure?",
            text: "You will not be able to undo this action once it is completed!",
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: `Yes, I am sure!`,
            cancelButtonText: `No, Cancel it!`
        }).then(result => {
            if (result.isConfirmed) {
                $.post({!! json_encode(url('/')) !!} + "/downloads/delete/" + appId + "/" + 6, {
                    _method: "DELETE",
                    _token: "{{ csrf_token() }}"
                }).then(function(data) {
                    console.log(data);
                    if (data == "success") {
                        swal.fire(
                            "Done!",
                            "Download was successfully deleted!.",
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
        });
    }
</script>
