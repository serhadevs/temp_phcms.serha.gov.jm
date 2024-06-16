<table class="table table-striped nowarp" id="food_clinics" style="width:100%">
    <thead>
        <tr>
            <th>App #</th>
            <th>Est. Name</th>
            <th>Address</th>
            <th>Telphone No.</th>
            <th>Payment Staus</th>
            <th>Clinic Date & Time</th>
            {{-- Enter after logic has been implemented --}}
            <th>No. of Permits</th>
            <th>No. of Employees</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($food_clinics as $application)
            <tr>
                <td>
                    {{ $application->id }}
                </td>
                <td>
                    {{ $application->name }}
                </td>
                <td>
                    {{ $application->address }}
                </td>
                <td>
                    {{ $application->telephone }}
                </td>
                <td class="text-center">
                    @if (empty($application->payment))
                        <span class="badge text-bg-danger">Not Paid</span>
                    @endif
                    @if (!empty($application->payment))
                        <span class="badge text-bg-success">Paid</span>
                    @endif
                </td>
                <td>
                    {{ $application->proposed_date }} - {{ $application->proposed_time }}
                </td>
                <td>
                    {{ $application->permits_count }}
                </td>
                <td>
                    {{ $application->no_of_employees }}
                </td>
               
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
@include('partials.messages.remove_entry_message')

    {{-- Button links --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.7/api/sum().js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataTableElement = document.getElementById('food_clinics');
    
            if (dataTableElement) {
                const dataTable = new DataTable(dataTableElement, {
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ],
                    scrollX: true,
                    responsive: true,
                    initComplete: function() {
                        // Assuming `loading` is a function or object that manages a loading indicator
                        if (typeof loading !== 'undefined') {
                            loading.close();
                        }
                    }
                });
            }
        });

    </script>

  
