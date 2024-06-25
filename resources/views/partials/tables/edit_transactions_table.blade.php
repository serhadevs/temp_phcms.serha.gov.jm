<table id="edit_transactions_table" class="table table-striped table-sm nowrap" style="width:100%;max-width:100%">
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
        <?php
        if (isset($system_operation_type_id)) {
            if ($system_operation_type_id == 1) {
                if ($app_type_id == 1) {
                    $transactions = $permit_application;
                }
            } elseif ($system_operation_type_id == 2) {
                $transactions = $application->healthInterviews;
            } elseif ($system_operation_type_id == 3) {
                $transactions = $est_application;
            } else {
                $transactions = $application;
            }
        } else {
            $transactions = $application;
        }
        ?>
        @foreach ($transactions->editTransactions as $edit)
            <tr>
                <td>{{ $edit->editType?->name }}</td>
                <td>{{ $edit->reason }}</td>
                <td>{{ $edit->user?->firstname . ' ' . $edit->user?->lastname }}</td>
                <td>{{ $edit->created_at }}</td>
                <td>
                    <button class="btn btn-primary mx-2 btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop"
                        onclick="popChangedTable({{ json_encode($edit->changedColumns) }})" type="button">
                        View
                    </button>
                </td>
            </tr>
        @endforeach
        @if (isset($system_operation_type_id))
            @if ($system_operation_type_id == 2)
                @foreach ($transactions->symptomsWithTrashed as $item)
                    @foreach ($item->editTransactions as $edit)
                        <tr>
                            <td>{{ $edit->editType?->name }}</td>
                            <td>{{ $edit->reason }}</td>
                            <td>{{ $edit->user?->firstname . ' ' . $edit->user?->lastname }}</td>
                            <td>{{ $edit->created_at }}</td>
                            <td>
                                <button class="btn btn-primary mx-2 btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop"
                                    onclick="popChangedTable({{ json_encode($edit->changedColumns) }})" type="button">
                                    View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @elseif($system_operation_type_id == 3)
                @foreach ($transactions->operators as $operator)
                    @foreach ($operator->editTransactions as $edit)
                        <tr>
                            <td>{{ $edit->editType?->name }}</td>
                            <td>{{ $edit->reason }}</td>
                            <td>{{ $edit->user?->firstname . ' ' . $edit->user?->lastname }}</td>
                            <td>{{ $edit->created_at }}</td>
                            <td>
                                <button class="btn btn-primary mx-2 btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#staticBackdrop"
                                    onclick="popChangedTable({{ json_encode($edit->changedColumns) }})" type="button">
                                    View
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @elseif($system_operation_type_id == 6)
                @foreach ($transactions->appointment?->first()?->editTransactions as $edit)
                    <tr>
                        <td>{{ $edit->editType?->name }}</td>
                        <td>{{ $edit->reason }}</td>
                        <td>{{ $edit->user?->firstname . ' ' . $edit->user?->lastname }}</td>
                        <td>{{ $edit->created_at }}</td>
                        <td>
                            <button class="btn btn-primary mx-2 btn-sm" data-bs-toggle="modal"
                                data-bs-target="#staticBackdrop"
                                onclick="popChangedTable({{ json_encode($edit->changedColumns) }})" type="button">
                                View
                            </button>
                        </td>
                    </tr>
                @endforeach
            @endif
        @endif
    </tbody>
</table>
@include('partials.modals.trans_columns_changed_modal')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    new DataTable('#edit_transactions_table', {
        scrollX: true
    });
</script>
<script>
    function popChangedTable(columns) {
        table = document.querySelector('#edit_cols_table tbody');
        table.innerHTML = "";
        columns.forEach((element) => {
            var tr = document.createElement('tr');
            var td1 = document.createElement('td');
            var td2 = document.createElement('td');
            var td3 = document.createElement('td');
            td1.innerHTML = element['column_name'] ? element['column_name'].toUpperCase().replace('_', ' ') :
                '';
            td2.innerHTML = element['old_value'] ? element['old_value'].toUpperCase() : '';
            td3.innerHTML = element['new_value'] ? element['new_value'].toUpperCase() : '';
            tr.append(td1, td2, td3);
            table.append(tr);
        })
    }
</script>
