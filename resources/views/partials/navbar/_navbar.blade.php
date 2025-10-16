@php
    $user = Auth::user();
@endphp

<nav class="navbar navbar-expand px-4 py-3">
    <div class="collapse navbar-collapse">
        <ul class="navbar-nav ms-auto align-items-center">
            <li class="nav-item me-3 dropdown">
                <button type="button" class="btn btn-primary position-relative dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false" data-bs-auto-close="outside">
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
                    @if (in_array(auth()->user()->role_id,[1,8]))
                        <li><a class="dropdown-item" href="{{ route('switch.location') }}">Switch Location</a></li>
                    @endif
                    <li><a class="dropdown-item" href="">View Profile</a></li>
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
