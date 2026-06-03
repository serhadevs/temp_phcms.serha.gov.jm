<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Protection & Privacy - PHCMS</title>
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
            padding: 4rem 0; /* Adjusted padding slightly for better edge alignment */
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

        .footer {
            background-color: #1a1a1a;
            color: #e0e0e0;
            padding: 50px 0 20px;
        }

        .footer h5 {
            color: white;
        }

        .footer a {
            color: #a0a0a0;
            text-decoration: none;
        }

        .footer a:hover {
            color: var(--gov-accent);
        }

        .footer-bottom {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #333;
            text-align: center;
            font-size: 0.85rem;
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
                            Data Protection & Privacy Notice
                        </h1>

                        <p class="text-muted">
                            Public Health and Certificate Management System (PHCMS)
                        </p>

                        <div class="alert alert-primary border-0">
                            SERHA is committed to protecting the privacy,
                            confidentiality, and security of all information
                            submitted through this platform.
                        </div>

                        <h3 class="section-title">Our Commitment</h3>

                        <p>
                            The South East Regional Health Authority (SERHA)
                            recognizes the importance of safeguarding personal
                            information entrusted to us through the Public
                            Health and Certificate Management System.
                        </p>

                        <p>
                            We implement administrative, technical, and
                            physical safeguards designed to prevent
                            unauthorized access, misuse, disclosure,
                            alteration, or loss of information.
                        </p>

                        <h3 class="section-title">Information We Collect</h3>

                        <ul>
                            <li>Applicant names and demographic information</li>
                            <li>Date of birth and contact details</li>
                            <li>Residential and mailing addresses</li>
                            <li>Food Handlers Permit information</li>
                            <li>Establishment Licence information</li>
                            <li>Payment transaction references</li>
                            <li>Health certification and screening records</li>
                            <li>System access and audit logs</li>
                        </ul>

                        <h3 class="section-title">How We Use Information</h3>

                        <ul>
                            <li>Processing permit applications</li>
                            <li>Generating certificates and permits</li>
                            <li>Identity verification</li>
                            <li>Supporting inspections and compliance activities</li>
                            <li>Maintaining public health records</li>
                            <li>Fraud prevention and security monitoring</li>
                        </ul>

                        <h3 class="section-title">How We Protect Your Data</h3>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <div class="security-card">
                                    <i class="bi bi-lock-fill"></i>
                                    <h5 class="mt-3">Encryption</h5>
                                    <p>
                                        All communications between users and
                                        PHCMS are secured using HTTPS/TLS
                                        encryption.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="security-card">
                                    <i class="bi bi-person-lock"></i>
                                    <h5 class="mt-3">Access Control</h5>
                                    <p>
                                        Access is limited to authorized staff
                                        members with approved responsibilities.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="security-card">
                                    <i class="bi bi-shield-check"></i>
                                    <h5 class="mt-3">Infrastructure Security</h5>
                                    <p>
                                        Firewalls, monitoring systems, and
                                        routine security updates protect
                                        our infrastructure.
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="security-card">
                                    <i class="bi bi-clipboard-data"></i>
                                    <h5 class="mt-3">Audit Logging</h5>
                                    <p>
                                        Activity logs help maintain
                                        accountability and support security
                                        investigations.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <h3 class="section-title">Information Sharing</h3>

                        <p>
                            SERHA does not sell, rent, or commercially
                            distribute personal information. Information is
                            only shared where required by law, public health
                            regulations, or authorized government functions.
                        </p>

                        <h3 class="section-title">Data Retention</h3>

                        <p>
                            Information is retained only as long as necessary
                            to satisfy legal, regulatory, and public health
                            requirements and is securely archived or disposed
                            of according to approved procedures.
                        </p>

                        <h3 class="section-title">Your Responsibilities</h3>

                        <ul>
                            <li>Provide accurate information.</li>
                            <li>Protect permit and certificate identifiers.</li>
                            <li>Safeguard account credentials.</li>
                            <li>Report suspicious activity.</li>
                            <li>Use PHCMS services responsibly.</li>
                        </ul>

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

    @include('verify.partials.footer')
  

</body>

</html>