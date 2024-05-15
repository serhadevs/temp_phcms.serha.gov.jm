<table id="inspections" class="table table-striped" style="width:100%;max-width:100%">
    <thead>
        <tr>
            <th>ID #</th>
            <th>Name</th>
            {{-- <th>Address</th> --}}
            <th>Type Food</th>
            <th class="text-nowrap">Payment Status</th>
            <th>Category</th>
            <th>Critical Score</th>
            <th>Overall Score</th>
            <th>Inspector</th>
            <th>Inspection Date</th>
            <th>Type</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inspections as $inspection)
            <tr>
                <td>{{ $inspection->id }}</td>
                <td>{{ $inspection->establishment_name }}</td>
                {{-- <td>{{ $inspection->establishment_address }}</td> --}}
                <td>{{ strtoupper($inspection->food_type) }}</td>
                <td class="text-center">
                    <span class="badge text-bg-{{ empty($inspection->payment) ? 'danger' : 'success' }}">
                        {{ empty($inspection->payment) ? 'Not Paid' : 'Paid' }}
                    </span>
                </td>
                <td>{{ $inspection->establishmentCategory?->name }}</td>
                <td>{{ $inspection->testResults?->critical_score }}</td>
                <td>{{ $inspection->testResults?->overall_score }}</td>
                <td>{{ $inspection->testResults?->staff_contact }}</td>
                <td>{{ $inspection->testResults?->test_date == "" ? "No Inspection" : \Carbon\Carbon::parse($inspection->testResults?->test_date)->format('d F Y') }}</td>
                <td><span class = "badge text-bg-success">{{ strtoupper($inspection->testResults?->visit_purpose) }}</span> </td>
                <td class="text-nowrap">
                   <a class="btn btn-primary btn-sm" href="/food-establishments/view/{{ $inspection->id }}">
                        View
                    </a>
            
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    new DataTable('#inspections', {
        responsive: true,
        scrollX: true,
        initComplete: function() {
            loading.close()
        }
    });
</script>
