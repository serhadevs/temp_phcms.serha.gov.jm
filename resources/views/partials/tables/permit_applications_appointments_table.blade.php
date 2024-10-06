<table class="table table-striped nowrap table-bordered" id="payment_info" style="width:100%">
    <thead>
        <tr>
            <th>
                Appointment Number
            </th>
            <th>
                Appointment Date
            </th>
            <th>
                Appointment Location
            </th>
            <th>
                Appointment Time
            </th>
            <th>
                Options
            </th>
        </tr>
    </thead>
    <tbody>
        @if (empty($permit_application->establishmentClinics))
            @foreach ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d F Y') }}</td>
                    <td>{{ $appointment->examDate?->examSites?->name }}</td>
                    <td>{{ $appointment->examDate?->exam_start_time }}</td>
                    <td>
                        <button href="" class="btn btn-warning btn-sm"
                            onclick="editAppointment({{ json_encode($appointment_available) }}, {{ json_encode($appointment->examDate?->id) }}, {{ json_encode($appointment->appointment_date) }}, {{ json_encode($appointment->id) }})"
                            type="button">
                            Edit
                        </button>
                    </td>
                </tr>
            @endforeach
        @else
        <tr>
            <td>N/A</td>
            <td>{{ $permit_application->establishmentClinics?->proposed_date }}</td>
            <td>{{ $permit_application->establishmentClinics?->address }}</td>
            <td>{{ $permit_application->establishmentClinics?->proposed_time }}</td>
            <td></td>
        </tr>
        @endif

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
    new DataTable('#payment_info', {
        scrollX: true,
        // responsive: true;
    });

    function editAppointment(appointments, selected_appointment, appointment_date, appointment_id) {
        swal.fire({
                title: "Select the exam day\n and location.",
                icon: "info",
                input: 'select',
                inputOptions: appointments,
                inputValue: selected_appointment,
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: `Yes, I am sure!`,
                cancelButtonText: `No, Cancel it!`
            })
            .then(result => {
                if (result.isConfirmed) {
                    swal.fire({
                        title: "Update appointment \ndate for exam.",
                        icon: "info",
                        input: 'date',
                        inputValue: appointment_date,
                        inputAttributes: {
                            required: true
                        },
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonText: `Yes, I am sure!`,
                        cancelButtonText: `No, Cancel it!`
                    }).then(result2 => {
                        if (result2.isConfirmed) {
                            if (selected_appointment == result.value && appointment_date == result2.value) {
                                swal.fire(
                                    "Oops! Something went wrong.",
                                    "Nothing was changed",
                                    "error");
                            } else {
                                swal.fire({
                                    title: "What is your reason for editing appointment?",
                                    text: "Reason will be recorded",
                                    icon: 'question',
                                    input: 'textarea',
                                    inputAttributes: {
                                        required: true
                                    },
                                    showConfirmButton: true,
                                    showCancelButton: true,
                                    confirmButtonText: "Update Appointment"
                                }).then((result3) => {
                                    if (result3.isConfirmed) {
                                        swal.fire({
                                            icon: 'warning',
                                            title: 'Are you sure you want to update appointment',
                                            showCancelButton: true,
                                            showConfirmButton: true,
                                            confirmButtonText: "Update Appointment"
                                        }).then((result4) => {
                                            if (result4.isConfirmed) {
                                                $.post({!! json_encode(url('/permit/application/update/appointment')) !!} + "/" +
                                                    appointment_id, {
                                                        _method: "PUT",
                                                        data: {
                                                            exam_date_id: result.value,
                                                            appointment_date: result2
                                                                .value,
                                                            edit_reason: result3.value
                                                        },
                                                        _token: "{{ csrf_token() }}"
                                                    }).then(function(data) {
                                                    if (data[0] == "success") {
                                                        swal.fire(
                                                            "Done!",
                                                            data[1],
                                                            "success").then(
                                                            esc => {
                                                                if (esc) {
                                                                    location
                                                                        .reload();
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
                        }
                    })
                }
            })
    }
</script>
