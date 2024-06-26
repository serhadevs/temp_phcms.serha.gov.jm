<table id="clinic_renewal" class="table-striped table table-bordered nowrap" style="width:100%;min-width:100%">
    <thead style="width:100%">
        <tr style="width:100%">
            <th class="sorting_disabled">
                <input type="checkbox" name="selectedCheckbox" id="selectAll">
            </th>
            <th>Application ID</th>
            <th>First Name</th>
            <th>Middle Name</th>
            <th>Last Name</th>
            <th>Date of Birth</th>
            <th>Gender</th>
            <th>TRN</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($permit_applications as $application)
            <tr>
                <td>
                    <input type="checkbox" name="status" id="{{ $application->id }}" class="form-check-input"
                        onchange="testing(this.checked, this.value)" value="{{ $application->id }}"
                        {{ old('renewable_permits') ? (in_array($application->id, explode(',', old('renewable_permits'))) ? 'checked' : '') : '' }}>
                </td>
                <td>{{ $application->id }}</td>
                <td>{{ $application->firstname }}</td>
                <td>{{ $application->middlename }}</td>
                <td>{{ $application->lastname }}</td>
                <td>{{ $application->date_of_birth }}</td>
                <td>{{ $application->gender }}</td>
                <td>{{ $application->trn }}</td>
                <td><button class="btn btn-primary btn-sm mx-2" data-bs-toggle="modal"
                        data-bs-target="#staticBackdrop-{{ $application->id }}" type="button">View</button></td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script>
    window.onload = () => {
        if (document.getElementById('renewable_permits').value != "") {
            document.getElementById('renewable_permits').value.split(',').forEach((element) => {
                selected_items.push(element);
            })

            document.getElementById('false-submit').style.display = "";
        }
    }
    var table = new DataTable('#clinic_renewal', {
        scrollX: true,
        "aoColumnDefs": [{
            "bSortable": false,
            "aTargets": ["sorting_disabled"]
        }],
        aLengthMenu: [
            [parseInt(10), parseInt(25), parseInt(50), parseInt(75), parseInt(100), parseInt(500),
                parseInt(1000), parseInt(5000), parseInt(-1)
            ],
            [10, 25, 50, 75, 100, 500, 1000, 5000, "All"]
        ],
        drawCallback: function(settings) {
            if (document.querySelectorAll('#clinic_renewal tbody input[type=checkbox]:checked').length ==
                this.api().rows({
                    page: 'current'
                }).count()) {
                document.getElementById('selectAll').checked = true;
            } else {
                document.getElementById('selectAll').checked = false;
            }
        }
    });

    table.columns.adjust().draw();

    document.getElementById('selectAll').addEventListener('change', function(e) {
        var all_checkboxes = document.querySelectorAll("#clinic_renewal tbody input[type=checkbox]");
        if (this.checked) {
            all_checkboxes.forEach((element) => {
                element.checked = true;
                element.onchange();
            })
        } else {
            all_checkboxes.forEach((element) => {
                element.checked = false;
                element.onchange();
            })
        }
        document.getElementById('renewable_permits').value = selected_items;
        document.getElementById('renewable_permits').onchange();
    })

    var selected_items = [];

    function testing(checkStatus, value) {
        if (checkStatus == true) {
            var index = selected_items.indexOf(value);
            if (index === -1) {
                selected_items.push(value);
                console.log(selected_items);
            }
        } else {
            var index = selected_items.indexOf(value);
            if (index !== -1) selected_items.splice(index, 1);
            console.log(selected_items);
        }
        document.getElementById('renewable_permits').value = selected_items;
        document.getElementById('renewable_permits').onchange();
    }
</script>
