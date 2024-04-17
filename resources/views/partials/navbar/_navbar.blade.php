<nav class="navbar navbar-expand px-4 py-3">
    <div class="navbar-collapse collapse">
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <form class="form-inline my-2 my-lg-0" action="/logout" method="POST">
                    @csrf
                    <i class="bi bi-person"></i> {{ Auth::user()->firstname }} {{ Auth::user()->lastname }}
                    @php
                        $facility = '';
                        switch(Auth::user()->facility_id) {
                            case 1:
                                $facility = 'St Catherine';
                                break;
                            case 2:
                                $facility = 'St Thomas';
                                break;
                            case 3:
                                $facility = 'Kingston and St Andrew';
                                break;
                            default:
                                $facility = 'Unknown Facility';
                        }
                    @endphp
                    <span class="badge bg-primary">{{ $facility }}</span>
                    <button class="btn btn-primary btn-sm my-2 my-sm-0" type="submit">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
        
    </div>
</nav>
