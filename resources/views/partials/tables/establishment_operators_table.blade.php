<table class="table table-striped nowrap table-bordered table-sm" id="est_operators" style="width:100%">
    <thead>
        <tr>
            <th>Operator ID</th>
            <th>Operator Name</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($est_application->operators as $operator)
            <tr>
                <td>{{ $operator->id }}</td>
                <td>{{ $operator->name_of_operator }}</td>
                <td class="text-nowrap">
                    <button class="btn btn-sm btn-warning"
                        onclick="editEstOperator({{ json_encode($operator->name_of_operator) }}, {{ json_encode($operator->id) }})">Edit
                        Operator</button>
                    <button class="btn btn-sm btn-danger"
                        onclick="deleteEstOperator({{ json_encode($est_application->id) }}, {{ json_encode($operator->id) }})">Delete
                        Operator</button>
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
    new DataTable('#est_operators', {
        scrollX: true
    });

    function editEstOperator(operator_name, operator_id) {
        swal.fire({
                title: "Edit operator name for \n application.",
                icon: "question",
                input: "text",
                inputValue: operator_name,
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
                    swal.fire({
                        title: 'What is the reason you are\n editing this operator?',
                        text: 'Reason will be recorded.',
                        icon: 'question',
                        input: 'textarea',
                        inputAttributes: {
                            required: true
                        },
                        showCancelButton: true,
                        showConfirmButton: true
                    }).then((result5) => {
                        if (result5.isConfirmed) {
                            swal.fire({
                                title: 'Are you sure you want to edit this operator?',
                                text: 'Ensure correct operator was selected',
                                icon: 'warning',
                                showCancelButton: true,
                                showConfirmButton: true
                            }).then((result6) => {
                                if (result6.isConfirmed) {
                                    $.post({!! json_encode(url('/food-establishments/edit/operators')) !!}, {
                                        _method: "POST",
                                        data: {
                                            name_of_operator: result.value,
                                            operator_id: operator_id,
                                            reason: result5.value
                                        },
                                        _token: "{{ csrf_token() }}"
                                    }).then(function(data) {
                                        if (data == "success") {
                                            swal.fire(
                                                "Done!",
                                                "Name of Operator was updated successfully.",
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
                            })
                        }
                    })
                }
            })
    }

    function deleteEstOperator(establishment_app_id, operator_id) {
        swal.fire({
            title: 'Are you sure you want to delete this operator?',
            text: 'This reason will be editted',
            icon: 'question',
            input: 'textarea',
            inputAttribute: {
                required: true
            },
            showCancelButton: true,
            showCancelButton: true
        }).then((result3) => {
            if (result3.isConfirmed) {
                swal.fire({
                        title: "Are you sure you \nwant to delete this \noperator?",
                        icon: "warning",
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonText: `Yes, I am sure!`,
                        cancelButtonText: `No, Cancel it!`
                    })
                    .then(result => {
                        if (result.isConfirmed) {
                            $.post({!! json_encode(url('/food-establishments/delete/operators')) !!}, {
                                _method: "POST",
                                data: {
                                    est_app_id: establishment_app_id,
                                    operator_id: operator_id,
                                    reason: result3.value
                                },
                                _token: "{{ csrf_token() }}"
                            }).then(function(data) {
                                if (data == "success") {
                                    swal.fire(
                                        "Done!",
                                        "Operator has been deleted successfully.",
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
                    })
            }
        })
    }
</script>
