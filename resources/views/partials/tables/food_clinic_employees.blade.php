<table class="table table-striped table-bordered" id="food_clinic_employees" style="width:100%">
    <thead>
        <tr>
            <th>Application ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Permit No.</th>
            <th>Signed Off Status</th>
            <th>Payment Status</th>
            <th>Photo Status</th>
            <th>Option</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($application->permits as $permit_application)
            <tr>
                <td>{{ $permit_application->id }}</td>
                <td>{{ $permit_application->firstname }}</td>
                <td>{{ $permit_application->lastname }}</td>
                <td>{{ $permit_application->permit_no }}</td>
                <td class="text-center"><span
                        class="badge text-bg-{{ $permit_application->sign_off_status == '1' ? 'success' : 'danger' }}">{{ $permit_application->sign_off_status == '1' ? 'COMPLETE' : 'INCOMPLETE' }}</span>
                </td>
                <td class="text-center">
                    <span
                        class="badge text-bg-{{ empty($permit_application->payment) ? 'danger' : 'success' }}">{{ empty($permit_application->payment) ? 'Not Paid' : 'Paid' }}</span>
                    </span>
                </td>
                <td class="text-center"><span
                        class="badge text-bg-{{ $permit_application->photo_upload == '' ? 'danger' : 'success' }}">{{ $permit_application->photo_upload == '' ? 'No Image' : 'Uploaded' }}</span>
                </td>
                <td class="">
                    <a href="/permit/application/edit/{{ $permit_application->id }}" class="btn btn-sm btn-warning">
                        Edit
                    </a>
                    <a href="/permit/view/{{ $permit_application->id }}" class="btn btn-sm btn-primary">
                        View
                    </a>
                    @if ($permit_application->sign_off_status != 1)
                        <button class="btn btn-sm btn-danger"
                            onclick="removeEntry('/permit/application',{{ json_encode($permit_application->id) }})">
                            Delete
                        </button>
                    @endif
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
    new DataTable('#food_clinic_employees', {
        scrollX: true,
        // initComplete: function() {
        //     loading.close()
        // },
        responsive: true
    });
</script>

<script>
    function removeEntry(path, entry_id) {
        swal.fire({
            title: 'What is the reason you are\n deleting this entry?',
            text: 'Reason will be recorded.',
            icon: 'question',
            input: "textarea",
            inputAttributes: {
                required: true
            },
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: "Delete Entry",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                swal.fire({
                    title: 'Are you sure you want to delete \n this entry?',
                    text: 'Ensure correct entry was selected.',
                    icon: 'info',
                    showCancelButton: true,
                    showCancelButton: true
                }).then((result2) => {
                    if (result2.isConfirmed) {
                        $.post({!! json_encode(url('/')) !!} + path + "/delete/" + entry_id, {
                            _method: "DELETE",
                            data: {
                                reason: result.value
                            },
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
        })
    }
</script>

@include('partials.messages.remove_entry_message')
