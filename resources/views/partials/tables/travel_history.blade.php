<table id="travel_history_table" class="table table-striped" style="width:100%;max-width:100%">
    <thead>
        <tr>
            <th>ID</th>
            <th>Destination</th>
            <th>Travel Date</th>
            <th>Date Added</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($application->travelHistory as $history)
            <tr>
                <td>{{ $history->id }}</td>
                <td>{{ $history->destination }}</td>
                <td>{{ $history->travel_date }}</td>
                <td>{{ $history->created_at }}</td>
                <td>
                    <button class="btn btn-sm btn-danger" onclick="removeEntry('/health-interview/travel-history', {{ json_encode($history->id) }})">Delete</button>
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
    new DataTable('#travel_history_table', {
        scrollX: true
    });
</script>
