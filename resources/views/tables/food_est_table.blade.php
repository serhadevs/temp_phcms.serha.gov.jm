<div class="row g-3 mt-2 mb-2">
    <table id="food_establishments" class="table table-striped wrap table-sm" style="width:100%;">
        <thead>
            <tr>

                <th>App #</th>
                <th>Permit Number</th>
                <th>Establishment Category</th>
                <th>Establishment Name</th>
                <th>Application Date</th>
                {{-- <th>Payment Status</th> --}}
                <th>Options</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($certificate as $cert)
                <tr>
                    <td>{{ $cert->id }}</td>
                    <td>{{ $cert->permit_no }}</td>
                    <td>{{ $cert->categories }}</td>
                    <td>{{ $cert->establishment_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($cert->application_date)->format('F d, Y') }}</td>
                    <td>
                        <div class="dropdown">
                            <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            </a>
                          
                            <ul class="dropdown-menu">
                              <li><a class="dropdown-item" href="#">View</a></li>
                              <li><a class="dropdown-item" href="#">Edit</a></li>
                              <li><a class="dropdown-item" href="#">Renew</a></li>
                              <li><a class="dropdown-item" href="#">Delete</a></li>
                            </ul>
                          </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>




    {{-- <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script> --}}

    <script src=" https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        // new DataTable('#food_establishments', {
        //     responsive: {
        //         details: {
        //             display: DataTable.Responsive.display.modal({
        //                 header: function(row) {
        //                     var data = row.data();
        //                     return 'Details for ' + data[0] + ' ' + data[1];
        //                 }
        //             }),
        //             renderer: DataTable.Responsive.renderer.tableAll({
        //                 tableClass: 'table'
        //             })
        //         }
        //     }
        // });

        new DataTable('#food_establishments', {
            responsive: true,
            "aaSorting": [
                [5, 'desc']
            ],

        });
    </script>
