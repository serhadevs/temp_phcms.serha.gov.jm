<table class="table table-bordered table-striped nowrap table-sm" id="messages" style="width:100%">
    <thead>
        <tr>
            <th>App #</th>
            <th>Firstname</th>
            <th>Lastname</th>
            <th>Email</th>
            <th>Email Type</th>
            <th>Sent By</th>
            <th>Date Sent</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach ($messages as $item)
            <tr>
                <td>{{ $item->permit_application_id}}</td>
                <td>{{ $item->permit_applications?->firstname }}</td>
                <td>{{ $item->permit_applications?->lastname }}</td>
                <td>{{ $item->to }}</td>
                <td>{{ $item->emailtypes?->name}}</td>
                <td>{{ $item->user?->firstname }} {{ $item->user?->lastname }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('F d Y') }}</td>
           
                {{-- <td>{{ $appointment?->applications-> }}</td> --}}
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
    new DataTable('#messages', {
        scrollX: true,
        "order": [[6, "asc"]]
    });
</script>


  
