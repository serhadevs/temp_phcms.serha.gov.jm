@php
    $user = Auth::user();
@endphp

<nav class="navbar navbar-expand px-4 py-3">
    <div class="collapse navbar-collapse">
        <!-- Search Bar -->
        {{-- <div class="d-flex me-auto">
            <input class="form-control" type="search" placeholder="Search..." aria-label="Search"
                style="width: 400px;" id="searchInput" readonly>
        </div> --}}

        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-3 dropdown">
                <button type="button" class="btn btn-primary position-relative dropdown-toggle"
                    data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                    <i class="bi bi-bell"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    {{-- @forelse ($user->notifications as $messages)
                        <li><a href="#" class="dropdown-item">{{ $messages->data['message'] }}</a></li> 
                    @empty
                        <li><a class="dropdown-item" href="#">No new notifications</a></li>
                    @endforelse --}}

                </ul>
            </li>
            <li class="nav-item dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false" data-bs-auto-close="outside">
                    <i class="bi bi-person"></i> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    @php
                        $facilityMap = [
                            1 => 'STC',
                            2 => 'STT',
                            3 => 'KSA',
                        ];
                        $facility = $facilityMap[Auth::user()->facility_id] ?? 'Unknown Facility';
                    @endphp
                    <li><a class="dropdown-item" href="#">Location: {{ $facility }}</a></li>
                    @if (in_array(auth()->user()->role_id, [1, 8]))
                        <li><a class="dropdown-item" href="{{ route('switch.location') }}">Switch Location</a></li>
                    @endif
                    {{-- <li><a class="dropdown-item" href="">View Profile</a></li> --}}
                    <li><a class="dropdown-item" href="{{ route('user.changepassword') }}">Change Password</a></li>

                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchModalLabel">Search</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('permit.search') }}" method="GET">
                    <div class="mb-3">
                        <label for="searchQuery" class="form-label">What are you looking for?</label>
                        <input type="text" name="q" id="searchQuery" placeholder="Enter search term..."
                            class="form-control" autofocus>
                    </div>

                    <input type="hidden" name="module" value="1">

                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchModal = new bootstrap.Modal(document.getElementById('searchModal'));
        const searchQuery = document.getElementById('searchQuery');
        
        // Open modal when clicking the search input
        searchInput.addEventListener('click', function() {
            searchModal.show();
            // Focus on the modal's search input after modal is shown
            setTimeout(() => {
                searchQuery.focus();
            }, 500);
        });
    });
</script>