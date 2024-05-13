<table id="edit_transactions_table" class="table table-striped" style="width:100%;max-width:100%">
    <thead>
        <tr>
            <th>Edit Type</th>
            <th>Reason for edit</th>
            <th>User</th>
            <th>Date Editted</th>
            <th>Columns Changed</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($permit_application->editTransactions as $edit)
            <tr>
                <td>{{ $edit->editType?->name }}</td>
                <td>{{ $edit->reason }}</td>
                <td>{{ $edit->user?->firstname . ' ' . $edit->user?->lastname }}</td>
                <td>{{ $edit->created_at }}</td>
                <td>
                    <button class="btn btn-primary mx-2 btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                    onclick="popChangedTable({{ json_encode( $edit->changedColumns) }})" type="button">
                        View
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    new DataTable('#edit_transactions_table', {
        scrollX: true
    });
</script>
