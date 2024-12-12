<table class="table table-striped nowrap table-bordered table-sm" id="exam_dates" style="width:100%">
    <thead>
        <tr>
            <th>Id</th>
            <th>Facility</th>
            <th>Exam Site</th>
            <th>Application Type</th>
            <th>Permit Category</th>
            <th>Exam Day</th>
            <th>Exam Time</th>
            <th>Date Created</th>
            <th>Actions</th>
         
        </tr>
    </thead>
    <tbody>
        @foreach ($exam_dates as $exam_site)
            <tr>
                <td>{{ $exam_site->id }}</td>
                <td>{{ $exam_site->facility?->name }}</td>
                <td>{{ $exam_site->examSites->name }}</td>
                <td>{{ $exam_site->application_type?->name }}</td>
                <td>{{ $exam_site->permitCategory?->name }}</td>
                <td>{{ $exam_days[$exam_site->exam_day] }}</td>
                <td>{{ $exam_site->exam_start_time }}</td>
                <td>{{ \Carbon\Carbon::parse($exam_site->created_at)->format('d F Y')}}</td>
                <td>
                    <a href="{{ route('examdate.edit',['id'=>$exam_site->id]) }}" class="btn btn-primary btn-sm">Edit</a>
                    <a href="{{ route('examdate.delete',['id'=>$exam_site->id]) }}" class="btn btn-danger btn-sm">Delete</a>
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
    new DataTable('#exam_dates', {
        scrollX: true,
        initComplete: function() {
            loading.close()
        }
        
    });
</script>


