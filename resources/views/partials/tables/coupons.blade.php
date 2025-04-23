<table class="table table-bordered table-striped nowrap table-sm" id="coupons" style="width:100%">
    <thead>
        <tr>
            <th>#</th>
            <th>Coupon Name</th>
            <th>Coupon Discount</th>
            <th>Coupon Validity</th>
            <th>Status</th>
            <th>Actions</th>
           
            
        </tr>
    </thead>
    
        @forelse ($coupons as $item)
        <tbody>
            <tr>
                <td>{{ $item->id}}</td>
                <td>{{ $item->coupon_name }}</td>
                <td>${{ $item->coupon_discount }}</td>
                <td>{{ \Carbon\Carbon::parse($item->coupon_validaity)->format('D, d F Y')}}</td>
                @if($item->coupon_validity >= Carbon\Carbon::now()->format('Y-m-d'))
                <td><span class="badge bg-success">Valid</span></td>
                
                @else
                <td><span class="badge bg-danger">Invalid</span></td>
                
                @endif
                <td>
                    <button class="btn btn-primary btn-sm">Edit</button>
                    <button class="btn btn-danger btn-sm">Delete</button>
                </td>
            </tr>
            <tbody>
        @empty
            <tr>
                <td colspan="6" class="text-center">No Coupons</td>
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
    new DataTable('#coupons', {
        scrollX: true,
        "order": [[6, "asc"]]
    });
</script>


  
