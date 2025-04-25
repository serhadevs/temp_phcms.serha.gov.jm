<table class="table table-bordered table-striped nowrap table-sm" id="questions" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Question</th>
            <th>Correct Answer</th>
            <th>Actions</th>


        </tr>
    </thead>

    @forelse ($questions as $item)
        <tbody>
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->question }}</td>
                <td>
                    @foreach ($item->answers as $answer)
                        @if ($answer->is_correct)
                            {{ $answer->answer }}
                        @endif
                    @endforeach
                </td>
                <td>
                    <a href="{{ route('questions.edit', ['id' => $item->id]) }}" class="btn btn-sm btn-success">Edit
                        Question</a>

                    @if ($item->answers->isEmpty())
                        <a href="{{ route('answers.create', ['id' => $item->id, 'exam_id' => $id]) }}" class="btn btn-sm btn-success">Add
                            Answer</a>
                    @else
                        <a href="{{ route('answers.create', ['id' => $item->id,'exam_id' => $id] ) }}" class="btn btn-sm btn-primary">Change
                            Answer</a>
                    @endif


                </td>
            </tr>
        <tbody>
        @empty
            <tr>
                <td colspan="6" class="text-center">No Questions</td>
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
    new DataTable('#questions', {
        scrollX: true,
        "order": [
            [6, "asc"]
        ]
    });
</script>
