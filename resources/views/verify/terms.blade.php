<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Use - PHCMS</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --gov-primary: #004a99;
            --gov-secondary: #00843D;
            --gov-accent: #FDB913;
            --text-dark: #212529;
            --bg-light: #f4f6f9;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: var(--bg-light);
        }

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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--gov-primary) !important;
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-dark) !important;
        }

        .nav-link:hover {
            color: var(--gov-primary) !important;
        }

        .main-content {
            flex-grow: 1;
        }

        .split-layout {
            min-height: calc(100vh - 140px);
        }

        .content-panel {
            background: var(--bg-light);
            padding: 4rem 0;
        }

        .policy-card {
            background: white;
            border-radius: 18px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .section-title {
            color: var(--gov-primary);
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .security-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            padding: 20px;
            height: 100%;
            background: #fff;
        }

        .security-card i {
            color: var(--gov-primary);
            font-size: 2rem;
        }

        @media (max-width: 991px) {
            .content-panel {
                padding: 2rem 1rem;
            }

            .policy-card {
                padding: 25px;
            }
        }
    </style>
</head>

<body>

    <div class="top-bar">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="d-flex align-items-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/0/0a/Flag_of_Jamaica.svg"
                    alt="Jamaica Flag"
                    style="height:16px;width:auto;"
                    class="me-2">
                Government of Jamaica
            </span>

            <div class="d-none d-md-block">
                <a href="#" class="text-white text-decoration-none me-3">Accessibility</a>
                <a href="#" class="text-white text-decoration-none">Contact Us</a>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center"
                href="https://www.serha.gov.jm">

                <img src="{{ asset('images/serha_logo.png') }}"
                    class="logo me-2"
                    alt="SERHA">

                <span class="fw-bold">
                    South East Regional Health Authority
                </span>
            </a>
        </div>
    </nav>

    <main class="main-content content-panel">
        <div class="container">
            <div class="row split-layout align-items-start">

                <div class="col-12">

                    <div class="policy-card">

                        <h1 class="fw-bold text-primary">
                            Terms of Use
                        </h1>

                        <p class="text-muted">
                            Public Health and Certificate Management System (PHCMS)
                        </p>

                        <div class="alert alert-primary border-0">
                            By accessing or using the PHCMS platform, you agree to be bound by these Terms of Use. If you do not agree to these terms, please do not use this system.
                        </div>

                        <h3 class="section-title">Acceptance of Terms</h3>

                        <p>
                            This platform is operated by the South East Regional Health Authority (SERHA). The services provided through the Public Health and Certificate Management System are subject to the following terms, which may be updated periodically without prior notice.
                        </p>

                        <h3 class="section-title">User Accounts and Security</h3>

                        <p>
                            To access certain features, you may be required to register for an account. You are solely responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.
                        </p>

                        <h3 class="section-title">Rules of Conduct</h3>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="security-card">
                                    <i class="bi bi-file-earmark-check"></i>
                                    <h5 class="mt-3">Data Accuracy</h5>
                                    <p>
                                        You agree to provide true, accurate, current, and complete information when submitting applications or interacting with the system.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="security-card">
                                    <i class="bi bi-shield-exclamation"></i>
                                    <h5 class="mt-3">Lawful Use</h5>
                                    <p>
                                        The system must only be used for its intended purpose. Any fraudulent application, misrepresentation, or forgery is strictly prohibited.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="security-card">
                                    <i class="bi bi-cpu"></i>
                                    <h5 class="mt-3">System Integrity</h5>
                                    <p>
                                        You must not attempt to bypass security protocols, introduce malicious code, or perform actions that burden our infrastructure.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="security-card">
                                    <i class="bi bi-x-circle"></i>
                                    <h5 class="mt-3">Prohibited Actions</h5>
                                    <p>
                                        Unauthorized scraping, data mining, or commercial exploitation of the system's database or architecture is forbidden.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <h3 class="section-title">Intellectual Property</h3>

                        <p>
                            All content, software, design, text, and graphics within the PHCMS are the property of the Government of Jamaica and SERHA, protected by applicable copyright and intellectual property laws.
                        </p>

                        <h3 class="section-title">Disclaimer of Warranties</h3>

                        <p>
                            While SERHA endeavors to ensure the platform operates smoothly, the system is provided on an "as is" and "as available" basis. We do not warrant that the system will be uninterrupted, error-free, or entirely secure at all times.
                        </p>

                        <h3 class="section-title">Limitation of Liability</h3>

                        <p>
                            Under no circumstances shall SERHA, its employees, or agents be liable for any direct, indirect, incidental, or consequential damages resulting from the use or inability to use the PHCMS platform.
                        </p>
                        
                        <h3 class="section-title">Termination of Access</h3>

                        <p>
                            SERHA reserves the right, in its sole discretion, to terminate or restrict your access to the platform at any time, without notice, for any violation of these Terms of Use or applicable public health laws.
                        </p>

                        <h3 class="section-title">Contact Information</h3>

                        <div class="alert alert-light border">
                            <strong>South East Regional Health Authority</strong><br>
                            Kingston, Jamaica<br>
                            Email: info@serha.gov.jm<br>
                            Telephone: (876) 754 - 1088
                        </div>

                    </div>

                </div>

            </div>
        </div>
    </main>

   
  
</body>
  @include('verify.partials.footer')

</html>