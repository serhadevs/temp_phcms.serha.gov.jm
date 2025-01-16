<table class="table table-striped nowrap table-bordered" id="downloads_table" style="width:100%">
    <thead>
        <tr>
            <th class="sorting_disabled">
                <input type="checkbox" class="form-check-input" name="selectedCheckbox" id="selectAll">
            </th>
            <th>Facility</th>
            <th>Application Amount</th>
            <th class="text-center">Download Status</th>
            <th>Clinic Date</th>
            <th>Date Received</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($downloads as $download)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input input" name="status" id="{{ $download->id }}"
                        value="{{ $download->id }}" onchange="handleCheckBox(this.checked, this.value)">
                </td>
                <td>
                    {{-- Need to delete this --}}
                    {{ count($download?->zippedApplications[0]?->payment) == 0 ? '' : $download?->zippedApplications[0]?->payment[0]?->facility?->name }}
                    {{-- @if ($application_type_id == 3)
                        {{ $download->zippedApplications->isNotEmpty() ? $download->zippedApplications[0]?->establishmentApplication->user?->facility?->name : '' }}
                    @elseif ($application_type_id == 1)
                    1
                        {{ $download->zippedApplications->isNotEmpty() ? $download->zippedApplications[0]?->permitApplication->user?->facility?->name : 'N/A' }}
                    @endif --}}
                </td>
                <td>{{ $download->application_amount }}</td>
                <td class="text-center">
                    @if ($download->download_date)
                        Downloaded on: {{ $download->download_date }}
                    @endif
                    @if (empty($download->download_date))
                        <span class="badge text-bg-success">NEW</span>
                    @endif
                </td>
                <td>
                    @if ($application_type_id == '1')
                        {{ substr($download->download_url, 23, 10) }}
                    @endif
                    @if ($application_type_id == '3')
                        {{ substr($download->download_url, 37, 10) }}
                    @endif
                </td>
                <td>
                    {{ $download->created_at }}
                </td>
                <td>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#printer-modal-{{ $download->id }}">
                        Download
                    </button>
                    <button class="btn btn-danger btn-sm"
                        onclick="deleteDownload({{ json_encode($download->id) }})">Delete</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<div id="input-holder" class="mt-3"></div>

</div>
<!-- Modal -->
@include('partials.modals.auth_printer_modal')

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<script>
    new DataTable('#downloads_table', {
        scrollX: true,
        initComplete: function() {
            loading.close()
        },
        // responsive: true,
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": ["sorting_disabled"]
        }],
    });

    function submitAuth(id) {
        form_name = "auth-form-" + id;
        if (!document.getElementById('email-' + id).value) {
            document.getElementById('error-email-' + id).innerText = "This is a required field";
        } else if (!/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/.test(document.getElementById('email-' + id).value)) {
            document.getElementById('error-email-' + id).innerText = "Please enter valid email";
        } else {
            document.getElementById('error-email-' + id).innerText = "";
        }

        if (!document.getElementById('password-' + id).value) {
            document.getElementById('error-password-' + id).innerText = "This is a required field";
        } else {
            document.getElementById('error-password-' + id).innerText = "";
        }

        if (document.getElementById('email-' + id).value && document.getElementById('password-' + id).value &&
            /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/.test(document.getElementById('email-' + id).value)) {
            document.getElementById(form_name).submit();
            document.getElementById("close-modal-" + id).click();
        }
    }

    $("#selectAll").change(function() {
        var checked = $(this).is(':checked');
        ev = document.createEvent('Event');
        ev.initEvent('change', true, false);
        if (checked) {
            $("#downloads_table input[type='checkbox']").not("[disabled]").prop('checked', true);
            el = document.querySelectorAll('.input');
            el.forEach(items => {
                items.dispatchEvent(ev);
            });
        } else {
            $("#downloads_table input[type='checkbox']").prop('checked', false);
            el = document.querySelectorAll('.input');
            el.forEach(items => {
                items.dispatchEvent(ev);
            });
        }
    });

    var selected_items = [];

    function handleCheckBox(checkStatus, value) {
        if (checkStatus == true) {
            selected_items.push(value);
            console.log(selected_items);

            div = $("#input-holder"),
                fields =
                ` <button type="submit"  id= "permit-submit" class="btn btn-primary" onclick ="deleteAllDownload();">REMOVE ALL</button>`;
            div.html(fields);

        } else {
            var index = selected_items.indexOf(value);
            if (index !== -1) selected_items.splice(index, 1);
            console.log(selected_items);
        }
    }

    function deleteDownload(downloadId) {
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
                $.post({!! json_encode(url('/')) !!} + "/downloads/" + downloadId, {
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

    function deleteAllDownload() {
        swal.fire({
            title: "Are you sure you want to remove all the selected downloads?",
            text: "Tip: Always ensure that you review all download package thoroughly before removal.",
            icon: "warning",
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: `Yes, I am sure!`,
            cancelButtonText: `No, Cancel it!`
        }).then(result => {
            if (result.isConfirmed) {
                $.post({!! json_encode(url('/downloads/deleteAll')) !!}, {
                    _method: "DELETE",
                    data: {
                        selected_items: selected_items
                    },
                    _token: "{{ csrf_token() }}"
                }).then(function(data) {
                    if (data == "success") {
                        swal.fire(
                            "Done!",
                            "Downloads(s) were successfully deleted",
                            "success").then(esc => {
                            if (esc) {
                                location.reload();
                            }
                        });
                    } else {
                        console.log(data);
                        swal.fire(
                            "Oops! Something went wrong.",
                            "Application(s) were NOT deleted",
                            "error");
                    }
                });
            }

        });
    }
</script>
