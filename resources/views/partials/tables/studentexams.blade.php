<table class="table table-bordered table-striped nowrap table-sm" id="studentexams" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Exam Name</th>
            <th>Actions</th>
           
            
        </tr>
    </thead>
    
        @forelse ($exams as $item)
        <tbody>
            <tr>
                <td>{{ $item->id}}</td>
                <td>{{ $item->title }}</td>
                <td>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                    data-bs-target="#addStudentExamModal"  data-id="{{ $item->id }}"
                    data-title="{{ $item->title }}">Edit</button>
                    <button class="btn btn-success btn-sm">Add Questions</button>
                    <button class="btn btn-danger btn-sm">Delete</button>
                </td>
            </tr>
            <tbody>
        @empty
            <tr>
                <td colspan="6" class="text-center">No Exams</td>
            </tr>
        @endforelse

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
    new DataTable('#studentexams', {
        scrollX: true,
        "order": [[6, "asc"]]
    });
</script>


  
