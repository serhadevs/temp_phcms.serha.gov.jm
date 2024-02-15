<table class="table table-striped no-warp" id="payment_info" style="width:100%">
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
        </tr>
    </thead>
    <tbody>
        @foreach (json_decode($json_appointments) as $appointment)
            <tr>
                <td>{{ $appointment->appointment_id }}</td>
                <td>{{ $appointment->appointment_date}}</td>
                <td>{{ $appointment->appointment_location }}</td>
                <td>{{ $appointment->appointment_time }}</td>
            </tr>
        @endforeach

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
        scrollX:true,
        // responsive: true;
    });
</script>
