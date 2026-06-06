  <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="https://www.serha.gov.jm" style="max-width: 75%;">
                <img src="{{ asset('images/serha_logo.png') }}" class="logo me-2" alt="SERHA"
                    style="height: 40px; width: auto; flex-shrink: 0;">

                <span class="text-wrap fw-bold" style="line-height: 1.2; font-size: 1.1rem;">
                    South East Regional Health Authority
                </span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="/verify-permit/home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/verify-permit/home">Food Handlers Permit</a></li>
                    <li class="nav-item"><a class="nav-link" href="/verify-establishments">Establisment Licenses</a></li>
                    {{-- <li class="nav-item"><a class="nav-link" href="#">Resources</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">News</a></li> --}}
                </ul>
                {{-- <a href="#" class="btn btn-outline-primary ms-lg-3 fw-bold" style="border-color: var(--gov-primary); color: var(--gov-primary);">Portal Login</a> --}}
            </div>
        </div>
    </nav>