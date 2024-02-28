<div class="row g-3 mt-2 mb-2">
    <table id="pemit_applications_sign_off" class="display table-striped table nowrap table-sm table-bordered"
        style="width:100%;max-width:100%">
        <thead>
            <tr>
                <th><input type="checkbox" name="selectedCheckbox" id="selectAll"></th>
                <th>View</th>
                <th>Status</th>
                <th>Application No.</th>
                <th>Establishment</th>
                <th>Permit #</th>
                <th>FirstName</th>
                <th>MiddleName</th>
                <th>LastName</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($applications as $application)
                <tr>
                    <td><input type="checkbox" name="status" id="" class="input" value={{ $application->id }} {{ $application->sign_off_status ?"disabled":"" }}>
                    </td>
                    <td><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            View
                        </button></td>
                    <td>
                       @if($application->sign_off_status)
                       <span class="badge bg-success">Approved</span>
                       @endif
                       @if (!$application->sign_off_status)
                       <span class="badge bg-danger">Awaiting</span>
                       @endif
                    </td>
                    <td>{{ $application->id }}</td>
                    <td>{{ $application->est_name }}</td>
                    <td>{{ $application->permit_no }}</td>
                    <td>{{ $application->permit_firstname }}</td>
                    <td>{{ $application->permit_middlename }}</td>
                    <td>{{ $application->permit_lastname }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
        <button class="btn btn-primary" onclick="approveSignOff(1)"> <i class="bi bi-box-arrow-in-right"></i>
            Approve</button>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        new DataTable('#pemit_applications_sign_off', {
            responsive: true,
            select: true
        });

        $("#selectAll").change(function() {
            var checked = $(this).is(':checked');
            ev = document.createEvent('Event');
            ev.initEvent('change', true, false);
            if (checked) {
                $("#pemit_applications_sign_off input[type='checkbox']").not("[disabled]").prop('checked', true);
                el = document.querySelectorAll('.input');
                el.forEach(items => {
                    items.dispatchEvent(ev);
                });
            } else {
                $("#pemit_applications_sign_off input[type='checkbox']").prop('checked', false);
                el = document.querySelectorAll('.input');
                el.forEach(items => {
                    items.dispatchEvent(ev);
                });
            }
        });

        var selected_items = [];
        $(".input[type=checkbox]").on('change', function(event) {
            if ($(this).is(':checked')) {
                selected_items.push($(this).val());
                console.log(selected_items);
            } else {
                var index = selected_items.indexOf($(this).val());
                if (index !== -1) selected_items.splice(index, 1);
                console.log(selected_items);
            }
        });

        function approveSignOff(appTypeId) {
            swal.fire({
                title: "Are you sure you want to approve the selected applications?",
                text: "Tip: Always ensure that you review each application thoroughly before approval.",
                icon: "warning",
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: `Yes, I am sure!`,
                cancelButtonText: `No, Cancel it!`
            }).then(result => {
                if (result.isConfirmed) {
                    if (result.isConfirmed) {
                        console.log("app type:" + appTypeId);
                        $.post({!! json_encode(url('/sign-off/approve')) !!}, {
                            _method: "POST",
                            data: {
                                selected_items: selected_items,
                                appTypeId: appTypeId
                            },
                            _token: "{{ csrf_token() }}"
                        }).then(function(data) {
                            console.log(data);
                            if (data == "success") {
                                swal.fire(
                                    "Done!",
                                    "Application(s) were successfully approved and will shortly be forwarded for printing.",
                                    "success").then(esc => {
                                    if (esc) {
                                        location.reload();
                                    }
                                });
                            } else {
                                swal.fire(
                                    "Oops! Something went wrong.",
                                    "Application(s) were NOT approved",
                                    "error");
                            }
                        })
                        // $.ajax({
                        //     url: '/sign-off/approve',
                        //     method: "POST",
                        //     data: {
                        //         selected_items: selected_items,
                        //         appTypeId: appTypeId
                        //     },
                        //     dataType:"text",
                        //     success: function(data) {
                        //         console.log(data);
                        //     }
                        // })
                        // .then(function(data) {
                        //     console.log(data)
                        // if (data == "success") {
                        //     swal(
                        //         "Done!",
                        //         "Application(s) were successfully approved and will shortly be forwarded for printing.",
                        //         "success").then(esc => {
                        //         if (esc) {
                        //             location.reload();
                        //         }
                        //     });
                        // } else {
                        //     swal(
                        //         "Oops! Something went wrong.",
                        //         "Application(s) were NOT approved",
                        //         "error");
                        // }
                        // });
                    }
                }
            })
            // .then((result) => {
            //     if (result.isConfirmed) {
            //         console.log("app type:" + appTypeId);
            //         $.ajax({
            //             url: '/sign-off/approve',
            //             method: "POST",
            //             data: {
            //                 selected_items: selected_items,
            //                 appTypeId: appTypeId
            //             }
            //         }).then(function(data) {
            //             console.log(data)
            //             if (data == "success") {
            //                 swal(
            //                     "Done!",
            //                     "Application(s) were successfully approved and will shortly be forwarded for printing.",
            //                     "success").then(esc => {
            //                     if (esc) {
            //                         location.reload();
            //                     }
            //                 });
            //             } else {
            //                 swal(
            //                     "Oops! Something went wrong.",
            //                     "Application(s) were NOT approved",
            //                     "error");
            //             }
            //         });
            //     }

            // });
        }
    </script>
</div>
