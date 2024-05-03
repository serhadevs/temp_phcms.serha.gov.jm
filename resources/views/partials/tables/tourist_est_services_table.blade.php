<table id="tourist_est_services_table" class="table table-striped " style="width:100%;">
    <thead>
        <tr>
            <th>Service Name</th>
            <th>Date Added</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($application->services as $service)
            <tr>
                <td>
                    {{ $service->name }}
                </td>
                <td>{{ date_format($service->created_at, 'Y-m-d H:i:s') }}</td>
                <td>
                    <button onclick="editService({{ json_encode($service->name) }}, {{ json_encode($service->id) }})"
                        class="btn-sm btn btn-warning">Edit Service</button>
                    <button onclick="deleteService({{ json_encode($service->id) }})" class="btn-sm btn btn-danger">Delete
                        Service</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

@if (isset($not_modal))
    <script>
        new DataTable('#tourist_est_services_table', {
            // responsive: true,
            scrollX: true
        });
    </script>
    <script>
        function editService(name, id) {
            swal.fire({
                    title: "Edit Service name for \n application.",
                    icon: "info",
                    input: "text",
                    inputValue: name,
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
                        $.post({!! json_encode(url('/tourist-establishments/services/update')) !!}, {
                            _method: "PUT",
                            data: {
                                name: result.value,
                                id: id
                            },
                            _token: "{{ csrf_token() }}"
                        }).then(function(data) {
                            if (data == "success") {
                                swal.fire(
                                    "Done!",
                                    "Service has been updated successfully.",
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

        function deleteService(id) {
            swal.fire({
                    title: "Are you sure you \nwant to delete this \nservice?",
                    icon: "warning",
                    showCancelButton: true,
                    showConfirmButton: true,
                    confirmButtonText: `Yes, I am sure!`,
                    cancelButtonText: `No, Cancel it!`
                })
                .then(result => {
                    if (result.isConfirmed) {
                        $.post({!! json_encode(url('/tourist-establishments/services/delete')) !!}, {
                            _method: "DELETE",
                            data: {
                                service_id: id
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
                })
        }
    </script>
@endif
