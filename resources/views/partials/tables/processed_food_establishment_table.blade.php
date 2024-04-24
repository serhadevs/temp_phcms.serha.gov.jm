<table id="food_establishments" class="table table-striped" style="width:100%;max-width:100%">
    <thead>
        <tr>
            <th>ID #</th>
            <th>Name</th>
            <th>Address</th>
            <th>Type Food</th>
            <th class="text-nowrap">Payment Status</th>
            <th>Category</th>
            <th>View</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($food_establishments as $est)
            <tr>
                <td>{{ $est->id }}</td>
                <td>{{ $est->establishment_name }}</td>
                <td>{{ $est->establishment_address }}</td>
                <td>{{ $est->food_type }}</td>
                <td class="text-center">
                    <span class="badge text-bg-{{ empty($est->payment) ? 'danger' : 'success' }}">
                        {{ empty($est->payment) ? 'Not Paid' : 'Paid' }}
                    </span>
                </td>
                <td>{{ $est->establishmentCategory?->name }}</td>
                <td class="text-nowrap">
                    <a class="btn btn-success btn-sm" href="/food-establishments/renewal/{{ $est->id }}">
                        Renew
                    </a>
                    <a class="btn btn-primary btn-sm" href="/food-establishments/view/{{ $est->id }}">
                        View
                    </a>
                    <a class="btn btn-warning btn-sm" href="/food-establishments/edit/{{ $est->id }}">
                        Edit
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
    new DataTable('#food_establishments', {
        responsive: true,
        scrollX: true,
    });
</script>
