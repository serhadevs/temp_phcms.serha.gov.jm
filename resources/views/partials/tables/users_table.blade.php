<div class="card shadow">
    <div class="card-header">
        <div class="row g-2">
            <div class="col-12 col-md-auto">
                <div class="h3 text-muted mb-2 mb-md-0">Users</div>
            </div>
            <div class="col-12 col-md-auto ms-md-auto">
                <div class="d-flex flex-column flex-sm-row gap-2">
                    @if (in_array(auth()->user()->role_id, [1, 2]))
                        <a href="/settings/user/create" class="btn btn-sm btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Add User
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                            data-bs-target="#resetPasswordModal"> <i class="bi bi-lock me-1"></i>Reset
                            Passwords</button>
                    @endif
                    <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal"
                        data-bs-target="#filterModal">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Filter Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="/settings/user/filter" method="POST">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label for="facility_id" class="form-label">Facilities</label>
                            <select class="form-select" id="facility_id" name="facility_id">
                                <option selected disabled>Select Facility</option>
                                @foreach ($facilities as $facility)
                                    <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Roles</label>
                            <select class="form-select" id="role_id" name="role_id">
                                <option selected disabled>Select Role</option>
                                @foreach ($roles as $id => $role)
                                    <option value="{{ $id }}">{{ $role }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Apply Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade @error('password') show @enderror" id="resetPasswordModal" tabindex="-1"
        aria-labelledby="filterModalLabel" aria-hidden="true" @error('password') style="display: block;" @enderror>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Reset All Passwords</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('users.reset.all') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="mb-3">
                            <label for="password" class="form-label">Enter your password</label>
                            <input type="password" name="password" id="password" placeholder="Password"
                                class="form-control @error('password') is-invalid @enderror">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_changed_at" class="form-label">Password Changed Date</label>
                            <input type="date" name="password_changed_at" id="password_changed_at"
                                class="form-control @error('password_changed_at') is-invalid @enderror"
                                value="{{ old('password_changed_at', date('Y-m-d')) }}">
                            @error('password_changed_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <p>Are you sure you want to do this? <strong>All passwords will be reset!</strong></p>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-danger">
                                Reset All
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- Add backdrop when there's an error --}}
    @error('password')
        <div class="modal-backdrop fade show"></div>
    @enderror




    <div class="card-body p-2 p-md-3">
        <!-- Mobile Card View (visible on small screens) -->
        <div class="d-md-none">
            @foreach ($users as $user)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h6 class="mb-1">{{ $user->firstname }} {{ $user->lastname }}</h6>
                                <small class="text-muted">{{ $roles[$user->role_id] }}</small>
                            </div>
                            <form method="post" action="{{ url('/settings/user/deactivate/' . $user->id) }}">
                                @csrf
                                @method('PUT')
                                <input type="text" hidden value="{{ $user->id }}" name="user_id">
                                <button type="submit"
                                    class="btn btn-{{ $user->status == 1 ? 'danger' : 'success' }} btn-sm">
                                    {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </div>

                        <div class="small mb-2">
                            <div class="mb-1">
                                <strong>Facility:</strong>
                                @php
                                    $facilityName =
                                        $user->facility_id == 1
                                            ? 'STC'
                                            : ($user->facility_id == 2
                                                ? 'STT'
                                                : ($user->facility_id == 3
                                                    ? 'KSA'
                                                    : 'Unknown'));
                                    echo $facilityName;
                                @endphp
                            </div>
                            <div class="mb-1"><strong>Phone:</strong> {{ $user->telephone }}</div>
                            <div class="mb-1"><strong>Email:</strong> {{ $user->email }}</div>
                            <div class="mb-1">
                                <strong>Status:</strong>
                                @if ($user->OnlineUser())
                                    <span class="badge bg-success">Online</span>
                                @else
                                    <span
                                        class="badge bg-danger">{{ Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}</span>
                                @endif
                            </div>
                            <div class="mb-1"><strong>Added:</strong>
                                {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</div>
                        </div>

                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="/settings/users/reset-password/{{ $user->id }}"
                                class="btn btn-sm btn-primary">
                                <i class="bi bi-key"></i> Reset
                            </a>
                            <a href="/settings/users/restore/{{ $user->id }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-arrow-clockwise"></i> Restore
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Desktop Table View (visible on medium+ screens) -->
        <div class="d-none d-md-block table-responsive">
            <table id="users" class="display table nowrap table-sm table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>Status</th>
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
                                    <button type="submit"
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
                                <div class="btn-group" role="group">
                                    <a href="{{ route('users.edit', ['id' => $user->id]) }}"
                                        class="btn btn-sm btn-primary">Edit</a>
                                    <a href="/settings/users/reset-password/{{ $user->id }}"
                                        class="btn btn-sm btn-primary">Reset</a>
                                    <a href="/settings/users/restore/{{ $user->id }}"
                                        class="btn btn-sm btn-primary">Restore</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <a href="{{ route('dashboard.dashboard') }}" class="btn btn-danger btn-sm">
            <i class="bi bi-arrow-left me-1"></i>Back to Dashboard
        </a>
    </div>
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
        new DataTable('#users', {
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
