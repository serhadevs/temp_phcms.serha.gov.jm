<nav class="navbar navbar-expand px-4 py-3">
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <div class="dropdown">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false" data-bs-auto-close="outside">
                    <i class="bi bi-person"></i> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                </button>
                <form class="dropdown-menu" action="/logout" method="POST">
                    @csrf

                    @php
                        $facility = '';
                        switch (Auth::user()->facility_id) {
                            case 1:
                                $facility = 'STC';
                                break;
                            case 2:
                                $facility = 'STT';
                                break;
                            case 3:
                                $facility = 'KSA';
                                break;
                            default:
                                $facility = 'Unknown Facility';
                        }
                    @endphp
                    <li><a class="dropdown-item" href="#">Location: {{ $facility }}</a></li>
                    @if(in_array(auth()->user()->role_id,[10]))
                    <li><a class="dropdown-item" href="/switch-location">Switch Location</a></li>
                    @endif
                    <li><a class="dropdown-item" href="">View Profile</a></li>
                    <li><a class="dropdown-item" href="{{ route('user.changepassword') }}">Change Password</a></li>

                    <li class="dropdown-item">
                        <button type="submit" class="btn btn-primary btn-sm">Logout</button>
                    </li>

                </form>
            </div>


        </ul>

    </div>
</nav>
