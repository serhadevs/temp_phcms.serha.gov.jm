<table id="tourist_est_managers_table" class="table table-striped table-bordered" style="width:100%;">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Post Held</th>
            <th>Qualification</th>
            <th>Nationality</th>
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
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

{{-- <script>
    new DataTable('#tourist_est_managers_table', {
        // responsive: true,
        scrollX: true
    });
</script> --}}
