<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Health and Certificate Management System - PHCMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --gov-primary: #004a99;
            /* Official Blue */
            --gov-secondary: #00843D;
            /* Jamaican Green */
            --gov-accent: #FDB913;
            /* Jamaican Gold */
            --text-dark: #212529;
            --bg-light: #f4f6f9;
            /* Light Gray background */
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Top Bar & Navbar Styles */
        .top-bar {
            background-color: var(--text-dark);
            color: white;
            padding: 8px 0;
            font-size: 0.85rem;
        }

        .logo {
            width: 2rem;
            height: 2rem;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 0;
            z-index: 10;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--gov-primary) !important;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-dark) !important;
            margin: 0 5px;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: var(--gov-primary) !important;
        }

        /* Split Screen Form Section */
        .main-content {
            flex-grow: 1;
            /* Pushes footer to the bottom */
        }

        .split-layout {
            min-height: calc(100vh - 140px);
            /* Adjusts for header height */
        }

        .info-panel {
            background-color: var(--gov-primary);
            color: white;
            padding: 5rem 4rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .info-panel h1 {
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .info-panel h3 {
            font-weight: 400;
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
        }

        .info-panel p {
            font-size: 0.95rem;
            line-height: 1.6;
            opacity: 0.85;
            margin-bottom: 3rem;
            max-width: 500px;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin-bottom: 4rem;
        }

        .feature-list li {
            margin-bottom: 1rem;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .custom-bullet {
            width: 10px;
            height: 10px;
            background-color: var(--gov-accent);
            border-radius: 50%;
            display: inline-block;
        }

        .badge-pill {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .badge-pill .dot {
            width: 8px;
            height: 8px;
            background-color: #10b981;
            border-radius: 50%;
        }

        .form-panel {
            background-color: var(--bg-light);
            padding: 4rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 550px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .form-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-icon {
            background-color: var(--gov-primary);
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #333;
            margin-bottom: 6px;
        }

        .form-text {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: -5px;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ced4da;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--gov-primary);
            box-shadow: 0 0 0 0.25rem rgba(0, 74, 153, 0.1);
        }

        .btn-retrieve {
            background-color: var(--gov-primary);
            color: white;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .btn-retrieve:hover {
            background-color: #003366;
            color: white;
        }

        .btn-cancel {
            background-color: white;
            color: #333;
            border: 1px solid #ccc;
            font-weight: 600;
            padding: 12px;
            border-radius: 8px;
        }

        .btn-cancel:hover {
            background-color: #f8f9fa;
        }

        .secure-connection-badge {
            margin-top: 30px;
            background: white;
            border: 1px solid #e0e0e0;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 0.8rem;
            color: #555;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }



        @keyframes scanPulse {
            0% {
                box-shadow: 0 0 0 rgba(0, 74, 153, 0.3);
            }

            50% {
                box-shadow: 0 0 18px rgba(0, 74, 153, 0.6);
            }

            100% {
                box-shadow: 0 0 0 rgba(0, 74, 153, 0.3);
            }
        }

        .btn-retrieve {
            animation: scanPulse 1.5s infinite;
        }

        .scan-text {
            font-size: 0.85rem;
            font-weight: 600;
            color: #004a99;
            min-height: 20px;
        }

        @media (max-width: 991px) {
            .info-panel {
                padding: 3rem 2rem;
                text-align: center;
                align-items: center;
            }

            .info-panel p {
                text-align: center;
            }

            .feature-list li {
                justify-content: center;
            }

            .form-panel {
                padding: 3rem 1rem;
            }

            .form-card {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

    <!-- Official Top Bar -->
    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="d-flex align-items-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/0a/Flag_of_Jamaica.svg" alt="Jamaica Flag"
                    style="height: 16px; width: auto;" class="me-2">
                Government of Jamaica
            </span>

            <div class="d-none d-md-block">
                <a href="#" class="text-white text-decoration-none me-3">Accessibility</a>
                <a href="#" class="text-white text-decoration-none">Contact Us</a>
            </div>
        </div>
    </div>


    @include('verify.partials.header')
    <!-- Main Content wrapper to push footer down -->
    <main class="main-content">
        <div class="container-fluid m-0 p-0">
            <div class="row m-0 split-layout">

                <!-- LEFT SIDE: Information -->
                <div class="col-lg-6 info-panel">
                    <h1>Food Establishments License Certificate</h1>
                    <h3>Official Secure Portal for Verification of your Food Establishments License</h3>

                    <p>The Food Establishments License Certificate serves as proof that you are certified to handle food
                        commercially in accordance with the Ministry of Health & Wellness Public Health Regulations of
                        1998. This system is powered by IDPro.</p>

                    <ul class="feature-list">
                        <li><span class="custom-bullet"></span> Proof that you are valid to handle food preparation</li>
                        <li><span class="custom-bullet"></span> Applicant identification information</li>
                        <li><span class="custom-bullet"></span> Active and expired License lookup</li>
                    </ul>

                    <div class="d-flex flex-wrap gap-3 justify-content-lg-start justify-content-center">
                        <div class="badge-pill">
                            <span class="dot"></span> Secure & Verified
                        </div>
                        <div class="badge-pill">
                            <i class="bi bi-clock"></i> Available 24/7
                        </div>
                    </div>
                </div>

                <!-- RIGHT SIDE: Form -->
                <div class="col-lg-6 form-panel">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    {{-- <form action="{{ route('verify.establishments.view') }}" method="post">
                        @csrf

                        <div class="form-card">
                            <div class="form-header">
                                <div class="form-icon">
                                    <i class="bi bi-building"></i>
                                </div>
                                <h2 class="h4 fw-bold">Food Establishments License Retrieval Form</h2>
                                <p class="text-muted small mb-0">Please provide the required information to generate &
                                    retrieve your certificate.</p>
                            </div>

                          
                                    

                                <!-- Permit Number -->
                                <div class="mb-3">
                                    <label class="form-label">Permit Number</label>
                                    <span class="form-text">
                                        Found on your payment receipt or previous permit document (e.g., KSA1234567)
                                    </span>

                                    <input type="text" class="form-control @error('permit_no') is-invalid @enderror"
                                        placeholder="Enter your Permit Number" name="permit_no"
                                        value="{{ old('permit_no') }}">

                                    @error('permit_no')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div> 

                                <!-- OR Divider -->
                                <div class="text-center fw-bold text-muted mb-2">
                                    &mdash; OR &mdash;
                                </div>

                                <!-- Application Number -->
                                <div class="mb-4">
                                    <label class="form-label">Application Number</label>
                                    <span class="form-text">
                                        Found on your payment receipt (e.g., 437483)
                                    </span>

                                    <input type="text"
                                        class="form-control @error('application_number') is-invalid @enderror"
                                        placeholder="Enter your application number" name="application_number"
                                        value="{{ old('application_number') }}">

                                    @error('application_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Buttons -->
                                <div class="row g-3 mt-2">
                                    <div class="col-6">
                                        <button type="button" class="btn btn-cancel w-100">
                                            <i class="bi bi-x"></i> Cancel
                                        </button>
                                    </div>
                                 
                                </div>
                           
                        </div>
                    </form> --}}
                    <h1>Coming Soon</h1>


                    <div class="secure-connection-badge">
                        <span class="dot"
                            style="width:8px; height:8px; background-color:#10b981; border-radius:50%;"></span>
                        Secure Connection
                    </div>

                </div>

            </div>
        </div>
    </main>

    @include('verify.partials.footer')

    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        const form = document.getElementById('retrievalForm');
        const submitBtn = document.getElementById('submitBtn');
        const responseMessage = document.getElementById('responseMessage');

        if (!form || !submitBtn) {
            console.error("Missing form or button IDs");
            return;
        }

        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            // UI RESET
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<span class="spinner-border spinner-border-sm"></span> Searching...';

            responseMessage.className = 'alert d-none mb-4';
            responseMessage.innerHTML = '';

            try {

                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (!response.ok) throw result;

                const redirectUrl = result.certificate_url;

                let progress = 0;
                let stage = 0;

                const stages = [
                    "Loading biometric data...",
                    "Connecting to IDPro Secure Platform...",
                    "Running identity verification scan...",
                    "Cross-checking national registry records...",
                    "Finalizing secure certificate validation..."
                ];

                // 🔥 FORCE UI RENDER BEFORE ANIMATION
                submitBtn.innerHTML = `
                <div style="width:100%">
                    <div id="scanText" class="scan-text">Initializing scan...</div>
                    <div class="progress mt-2" style="height:6px;">
                        <div id="scanBar" class="progress-bar progress-bar-striped progress-bar-animated"
                             style="width:0%"></div>
                    </div>
                </div>
            `;

                await new Promise(requestAnimationFrame);

                const scanText = document.getElementById('scanText');
                const scanBar = document.getElementById('scanBar');

                function typeText(text, cb) {
                    let i = 0;
                    scanText.innerHTML = "";

                    const typing = setInterval(() => {
                        scanText.innerHTML += text.charAt(i);
                        i++;
                        if (i === text.length) {
                            clearInterval(typing);
                            cb?.();
                        }
                    }, 25);
                }

                function runScan() {

                    if (stage >= stages.length) {
                        scanBar.style.width = "100%";
                        scanText.innerHTML = "Verification complete. Redirecting...";

                        setTimeout(() => {
                            window.location.href = redirectUrl;
                        }, 800);

                        return;
                    }

                    typeText(stages[stage], () => {
                        progress += 20;
                        scanBar.style.width = progress + "%";
                        stage++;

                        setTimeout(runScan, 900);
                    });
                }

                // START AFTER RENDER CYCLE
                setTimeout(runScan, 200);

            } catch (error) {

                console.error(error);

                responseMessage.className = 'alert alert-danger mb-4';
                responseMessage.innerHTML =
                    error.message || 'An error occurred while retrieving the permit.';
            }

        });

    });
</script>



</html>
