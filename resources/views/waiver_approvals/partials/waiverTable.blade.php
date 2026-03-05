 <div class="d-none d-md-block table-responsive">
     <table id="waivers" class="display table nowrap table-sm table-bordered" style="width:100%">
         <thead>
             <tr>
                 <th>App Id</th>
                 <th>Establishment Name</th>
                 <th>Reason for Waiver</th>
                 <th>Requested By</th>
                 <th>Waiver Amount</th>
                 <th>Actions</th>

             </tr>
         </thead>
         <tbody>
             @forelse ($waivers as $item)
                 <tr>
                     <td>{{ $item->application_id }}</td>
                     <td>{{ $item->establishment->establishment_name }}</td>
                     <td>{{ $item->waiver_reason }}</td>
                     <td>{{ $item->user->firstname }} {{ $item->user->lastname }}</td>
                     <td>J${{ number_format($item->amount, 2) }}</td>
                     <td>
                        
                         <button type="button" class="btn btn-success btn-sm"
                             onclick="approveWaiver({{ $item->id }})">
                             Approve
                         </button>
                         <a href="/" class="btn btn-danger btn-sm">Reject</a>
                     </td>



                 </tr>

             @empty
                 <tr>No waivers</tr>
             @endforelse
         </tbody>
     </table>
 </div>


 <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
 <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
 <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
 <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
 <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

 <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
 <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
 <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
 <script src="https://cdn.datatables.net/plug-ins/1.13.7/api/sum().js"></script>

 <script>
     // Only initialize DataTable on desktop (md and up)
     if (window.innerWidth >= 768) {
         new DataTable('#waivers', {
             scrollX: true,
             initComplete: function() {
                 if (typeof loading !== 'undefined') {
                     loading.close()
                 }
             },
             dom: 'Bfrtip',
             buttons: [
                 'copy', 'csv', 'excel', 'pdf', 'print'
             ],
             "order": [],
             "footerCallback": function(row, data, start, end, display) {
                 var api = this.api(),
                     data;
             },
             "aoColumnDefs": [{
                 "bSortable": false,
                 "aTargets": ["sorting_disabled"]
             }],
         });

         window.onload = () => {
             buttons = document.querySelectorAll("div.dt-buttons button");
             buttons.forEach((element) => {
                 element.classList.add("btn");
                 element.classList.add("btn-secondary");
                 element.classList.add("btn-sm");
             })
         }
     }
 </script>

 <style>
     /* Mobile optimizations */
     @media (max-width: 767.98px) {
         .card-body {
             max-height: 70vh;
             overflow-y: auto;
         }
     }
 </style>

 <script>
     function approveWaiver(waiverId) {
         const tokenMeta = document.querySelector('meta[name="csrf-token"]');
         const csrfToken = tokenMeta ? tokenMeta.content : '{{ csrf_token() }}'; // fallback

         Swal.fire({
             title: 'Are you sure?',
             text: "You are about to approve this waiver request.",
             icon: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#3085d6',
             cancelButtonColor: '#d33',
             confirmButtonText: 'Yes, approve it!'
         }).then((result) => {
             if (result.isConfirmed) {
                 Swal.fire({
                     title: 'Processing...',
                     text: 'Please wait while we approve the waiver.',
                     allowOutsideClick: false,
                     didOpen: () => Swal.showLoading()
                 });

                 fetch("{!! url('/waivers/approve') !!}/" + waiverId, {
                         method: 'POST',
                         headers: {
                             'X-CSRF-TOKEN': csrfToken,
                             'Content-Type': 'application/json',
                             'Accept': 'application/json',
                         },
                         body: JSON.stringify({})
                     })
                     .then(response => {
                         if (!response.ok) {
                             return response.json().then(err => {
                                 throw new Error(err.message || 'Request failed');
                             });
                         }
                         return response.json();
                     })
                     .then(data => {
                         Swal.fire({
                             title: data.status === 'success' ? 'Approved!' : 'Error!',
                             text: data.message,
                             icon: data.status === 'success' ? 'success' : 'error',
                             confirmButtonColor: '#3085d6',
                         }).then(() => {
                             if (data.status === 'success') window.location.reload();
                         });
                     })
                     .catch(error => {
                         console.error('Error:', error);
                         Swal.fire({
                             title: 'Error!',
                             text: error.message ||
                                 'Something went wrong while approving the waiver.',
                             icon: 'error',
                             confirmButtonColor: '#d33',
                         });
                     });
             }
         });
     }
 </script>
