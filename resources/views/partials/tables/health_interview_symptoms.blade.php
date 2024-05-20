<table id="symptoms_table" class="table table-striped" style="width:100%;max-width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Date Added</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($application->healthInterviews?->healthInterviewSymptom as $symp)
            <tr>
                <td>{{ $symp->id }}</td>
                <td>{{ $symp->symptoms?->name }}</td>
                <td>{{ $symp->created_at }}</td>
                <td>
                    <button class="btn btn-danger btn-sm"
                        onclick="removeEntry('/health-interview/symptoms', {{ json_encode($symp->id) }})">Delete</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@include('partials.messages.remove_entry_message')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    new DataTable('#symptoms_table', {
        scrollX: true
    });
</script>
