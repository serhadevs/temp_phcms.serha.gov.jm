<div class="card shadow">
    <div class="card-header">
        <div class="row justify-content-between">
            <div class="col">
                <div class="h3 text-muted">Users</div>
            </div>
            <div class="col-auto d-flex justify-flex-end ml-0">
                @if (in_array(auth()->user()->role_id, [1, 2]))
                    <div class="me-2">
                        <a href="/settings/user/create" class="btn btn-sm btn-success">Add User</a>
                    </div>
                @endif
                <div>
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#filterModal">
                        Filter
                    </button>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Users</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Add your filter form here -->
                                <form action="/settings/user/filter" method="POST">
                                    @csrf
                                    @method('POST')
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Facilities</label>
                                        <select class="form-select" id="facility_id" name="facility_id">
                                            <option selected disabled>Select Facility</option>
                                            @foreach ($facilities as $facility)
                                                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                            @endforeach
                                        </select>

                                        <label for="role" class="form-label">Roles</label>
                                        <select class="form-select" id="" name="role_id">
                                            <option selected disabled>Role</option>
                                            @foreach ($roles as $id => $role)
                                                <option value="{{ $id }}">{{ $role }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <table id="users" class="display table nowrap table-sm table-bordered"
                style="width:100%;max-width:100%">
                <thead>
                    <tr>
                        <th>Statue</th>
                        <th>Firstname</th>
                        <th>LastName</th>
                        <th>Facility</th>
                        <th>Role</th>
                        <th>Telephone</th>
                        <th>Email</th>
                        <th>Date Added</th>
                        <th>Last Updated</th>
                        <th>Last Seen</th>
                        <th>Options</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($users as $user)
                        <tr>
                            <td>
                                <form method="post" action="{{ url('/settings/user/deactivate/' . $user->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" hidden value="{{ $user->id }}" name="user_id">
                                    <button type="submit" name = "submit"
                                        class="btn btn-{{ $user->status == 1 ? 'danger' : 'success' }} btn-sm">
                                        {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>


                            </td>
                            <td>{{ $user->firstname }}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>
                                @php
                                    $facilityName =
                                        $user->facility_id == 1
                                            ? 'STC'
                                            : ($user->facility_id == 2
                                                ? 'STT'
                                                : ($user->facility_id == 3
                                                    ? 'KSA'
                                                    : 'Unknown Facility'));
                                    echo $facilityName;
                                @endphp
                            </td>
                            <td>{{ $roles[$user->role_id] }}</td>
                            <td>{{ $user->telephone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('F d Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('F d Y') }}</td>
                            <td>
                                @if ($user->OnlineUser())
                                    <span class="badge bg-success">Online</span>
                                @else
                                    <span
                                        class="badge bg-danger">{{ Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('users.edit', ['id' => $user->id]) }}"
                                    class="btn btn-sm btn-primary">Edit</a>
                                <a href ="/settings/users/reset-password/{{ $user->id }}"
                                    class="btn btn-sm btn-primary">Reset</a>
                                <a href ="/settings/users/restore/{{ $user->id }}"class="btn btn-sm btn-primary">Restore</button>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    <div class="card-footer">
        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger">Back to Dashboard</a>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/plug-ins/1.13.7/api/sum().js"></script>
    {{-- 
 <script>
     new DataTable('#users', {
         responsive: true,
         scrollX:true,
     });
 </script>
     --}}

    <script>
        new DataTable('#users', {
            scrollX: true,
            initComplete: function() {
                loading.close()
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
    </script>

    <script>
        window.onload = () => {
            buttons = document.querySelectorAll("div.dt-buttons button");
            buttons.forEach((element) => {
                element.classList.add("btn");
                element.classList.add("btn-secondary")
            })
        }
    </script>
