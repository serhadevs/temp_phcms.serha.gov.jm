<table id="tourist_est_managers_table" class="table table-striped nowrap table-bordered" style="width:100%;">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Post Held</th>
            <th>Qualification</th>
            <th>Nationality</th>
            @if (isset($not_modal))
                <th>Options</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach ($application->managers as $manager)
            <tr>
                <td>{{ $manager->firstname }}</td>
                <td>{{ $manager->lastname }}</td>
                <td>{{ $manager->post_held }}</td>
                <td>{{ $manager->qualifications }}</td>
                <td>{{ $manager->nationality }}</td>
                @if (isset($not_modal))
                    <td class="text-nowrap">
                        <a href="/tourist-establishments/managers/edit/{{ $manager->id }}"
                            class="btn btn-sm btn-warning">Edit Managers</a>
                        <button onclick="deleteManager({{ json_encode($manager->id) }})"
                            class="btn btn-sm btn-danger">Delete Manager</button>
                    </td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

@if (isset($not_modal))
    <script>
        new DataTable('#tourist_est_managers_table', {
            // responsive: true,
            scrollX: true
        });

        function deleteManager(id) {
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
            }).then((result3) => {
                if (result3.isConfirmed) {
                    swal.fire({
                            title: "Are you sure you \nwant to delete this \nmanager?",
                            icon: "warning",
                            showCancelButton: true,
                            showConfirmButton: true,
                            confirmButtonText: `Yes, I am sure!`,
                            cancelButtonText: `No, Cancel it!`
                        })
                        .then(result => {
                            if (result.isConfirmed) {
                                $.post({!! json_encode(url('/tourist-establishments/managers/delete')) !!} + "/" + id, {
                                    _method: "DELETE",
                                    data: {
                                        edit_reason: result3.value
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
                                })
                            }
                        })

                }
            })
        }
    </script>
@endif
