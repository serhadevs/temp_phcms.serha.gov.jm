<table class="table table-striped table-bordered" id="establishment_categories_table" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Active Status</th>
            <th>Actions</th>
    </thead>
    <tbody>
        @foreach ($mailing_list as $mailing)
            <tr>
                <td>{{ $mailing->id }}</td>
                <td>{{ $mailing->first_name }}</td>
                <td>{{ $mailing->last_name }}</td>
                <td>{{ $mailing->email }}</td>
                <td>
                    <span class="badge text-bg-{{ $mailing->is_active == '1' ? 'success' : 'danger' }}">
                        {{ $mailing->is_active == '1' ? 'Active' : 'Deactivated' }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-warning"
                        onclick="edit({{ json_encode($mailing->email) }},{{ json_encode($mailing->first_name) }},{{ json_encode($mailing->last_name) }}, {{ json_encode($mailing->id) }})">
                        Edit
                    </button>
                    <button class="btn btn-danger" class="btn btn-sm btn-danger"
                        onclick="deletePersonnel({{ json_encode($mailing->id) }})">
                        Delete
                    </button>
                    <button class="btn {{ $mailing->is_active == '1' ? 'btn-info' : 'btn-success' }}"
                        onclick="deactivatePersonnel({{ json_encode($mailing->id) }}, {{ json_encode($mailing->is_active) }})">
                        {{ $mailing->is_active == '1' ? 'Deactivate' : 'Activate' }}
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    function edit(email, first_name, last_name, mailing_id) {
        swal.fire({
            title: "Edit Mailing List Personnel",
            text: "Update Email Address",
            icon: "question",
            input: "text",
            inputValue: email,
            inputAttributes: {
                required: true
            },
            showCancelButton: true,
            showConfirmButton: true
        }).then((result) => {
            swal.fire({
                title: "Edit Mailing List Personnel",
                text: "Update First Name",
                icon: "question",
                input: "text",
                inputValue: first_name,
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                showConfirmButton: true
            }).then((result2) => {
                swal.fire({
                    title: "Edit Mailing List Personnel",
                    text: "Update Last Name",
                    icon: "question",
                    input: "text",
                    inputValue: last_name,
                    inputAttributes: {
                        required: true
                    },
                    showCancelButton: true,
                    showConfirmButton: true
                }).then((result3) => {
                    if (result.isConfirmed) {
                        swal.fire({
                            title: "Are you sure you want to update this personnel?",
                            text: "Ensure correct email was entered",
                            icon: "warning",
                            showCancelButton: true,
                            showConfirmButton: true
                        }).then((result4) => {
                            if (result4.isConfirmed) {
                                $.post({!! json_encode(url('/mailing-list')) !!} + "/" + mailing_id, {
                                    data: {
                                        email: result.value,
                                        first_name: result2.value,
                                        last_name: result3.value
                                    },
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
                })

            })
        })
    }
</script>
<script>
    function deletePersonnel(mailing_id) {
        swal.fire({
            title: "Are you sure you want to delete this mailing list personnel?",
            text: "Ensure correct personnel was selected",
            icon: "warning",
            showCancelButton: true,
            showConfirmButton: true
        }).then((result3) => {
            if (result3.isConfirmed) {
                $.post({!! json_encode(url('mailing-list')) !!} + "/" + mailing_id, {
                    data: {},
                    _method: "DELETE",
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
<script>
    function deactivatePersonnel(mailing_id, active_status) {
        swal.fire({
            title: `Are you sure you want to ${active_status == 1 ? 'Deactivate' : 'Activate'} this mailing list personnel?`,
            text: "Ensure correct personnel was selected",
            icon: "warning",
            showCancelButton: true,
            showConfirmButton: true
        }).then((result3) => {
            if (result3.isConfirmed) {
                $.post({!! json_encode(url('/mailing-list/active')) !!} + "/" + mailing_id, {
                    data: {},
                    _method: "DELETE",
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
    new DataTable('#establishment_categories_table', {
        scrollX: true,
    });
</script>
