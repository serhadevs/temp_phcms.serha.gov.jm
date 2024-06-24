<table id="food_establishments" class="table table-striped table-bordered nowrap table-responsive-sm" style="width:100%;max-width:100%">
    <thead>
        <tr>
            <th class="text-nowrap">ID #</th>
            <th>Name</th>
            <th>Address</th>
            <th class="text-nowrap">Type Food</th>
            <th class="text-nowrap">Payment Status</th>
            <th class="text-nowrap">Payment Date</th>
            <th class="text-nowrap">Telphone No.</th>
            <th>Category</th>
            <th>Sign Off Status</th>
            <th>Opertators</th>
            <th class="text-nowrap">Added By</th>
            <th class="text-nowrap">Expiry Date</th>
            <th class="text-nowrap">App Type</th>
            <th>Options</th>
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
                <td class="text-nowrap">
                    {{ !empty($est->payment) ? Carbon\Carbon::parse($est->payment?->created_at)->format('F j, Y, g:i a') : 'N/A' }}
                </td>
                <td class="text-nowrap">{{ $est->telephone }}</td>
                <td>{{ $est->establishmentCategory?->name }}</td>
                <td><span
                        class="badge text-bg-{{ !empty($est->signOff) ? 'success' : 'danger' }}">{{ !empty($est->signOff) ? 'COMPLETE' : 'INCOMPLETE' }}</span>
                </td>
                <td>
                    @foreach ($est->operators as $operator)
                        <span class="">{{ $operator?->name_of_operator . "\n" }}</span>
                    @endforeach
                </td>
                <td>
                    {{ strtoupper($est?->user?->firstname[0] . '.' . $est?->user?->lastname) }}
                </td>
                <td>
                    {{ !empty($est->signOff) ? $est?->signOff?->expiry_date : 'N/A' }}
                </td>
                <td>
                    {{ !empty($est->renewal) ? 'RENEWAL' : 'NEW' }}
                </td>
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
                    @if ($est->sign_off_status != '1')
                        <button class="btn btn-sm btn-danger"
                            onclick="removeEntry('/food-establishments', {{ json_encode($est->id) }})">
                            Remove
                        </button>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@include('partials.messages.remove_entry_message')
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

@if (isset($is_general_report))
    {{-- Button Links --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.7/api/sum().js"></script>
    <script>
        new DataTable('#food_establishments', {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            scrollX: true,
            initComplete: function() {
                loading.close()
            }
        });
    </script>
@else
    <script>
        new DataTable('#food_establishments', {
            // responsive: true,
            scrollX: true,
            initComplete: function() {
                loading.close()
            }
        });
    </script>
@endif
