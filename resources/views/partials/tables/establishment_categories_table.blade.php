<table class="table table-striped table-bordered" id="establishment_categories_table" style="width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Establishment Category Name</th>
            <th>Options</th>
    </thead>
    <tbody>
        @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>
                    <button class="btn btn-sm btn-success"
                        onclick="edit({{ json_encode($category->name) }}, {{ json_encode($category->id) }})">
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteEst({{ json_encode($category->id) }})">
                        Delete
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<script>
    function edit(est_cat_name, est_cat_id) {
        swal.fire({
            title: "Edit Establishment Category",
            text: "Update Category Name",
            icon: "question",
            input: "text",
            inputValue: est_cat_name,
            inputAttributes: {
                required: true
            },
            showCancelButton: true,
            showConfirmButton: true
        }).then((result) => {
            if (result.isConfirmed) {
                swal.fire({
                    title: "What is the reason you are editing this category?",
                    text: "Reason will be recorded",
                    input: "textarea",
                    icon: "question",
                    showCancelButton: true,
                    showConfirmButton: true
                }).then((result2) => {
                    if (result2.isConfirmed) {
                        swal.fire({
                            title: "Are you sure you want to update this category?",
                            text: "Ensure correct name was entered",
                            icon: "warning",
                            showCancelButton: true,
                            showConfirmButton: true
                        }).then((result3) => {
                            if (result3.isConfirmed) {
                                $.post({!! json_encode(url('/admin/establishment-categories/update')) !!}, {
                                    data: {
                                        est_cat_updated: result.value,
                                        est_cat_id: est_cat_id,
                                        reason: result2.value
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
            }
        })
    }
</script>
<script>
    function deleteEst(est_cat_id) {
        swal.fire({
            title: "What is the reason you are deleting this category?",
            text: "Reason will be recorded",
            input: "textarea",
            icon: "question",
            showCancelButton: true,
            showConfirmButton: true
        }).then((result2) => {
            if (result2.isConfirmed) {
                swal.fire({
                    title: "Are you sure you want to delete this category?",
                    text: "Ensure correct name was selected",
                    icon: "warning",
                    showCancelButton: true,
                    showConfirmButton: true
                }).then((result3) => {
                    if (result3.isConfirmed) {
                        $.post({!! json_encode(url('/admin/establishment-categories/delete')) !!}, {
                            data: {
                                est_cat_id: est_cat_id,
                                reason: result2.value
                            },
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
        })
    }
</script>
{{-- <script>
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
</script> --}}
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
