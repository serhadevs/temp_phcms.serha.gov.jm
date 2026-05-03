<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Food Handlers Permit Confirmation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @page {
            margin: 15mm;
            size: A4;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }

            .permit-wrapper {
                page-break-after: avoid;
            }

            .row {
                page-break-inside: avoid;
            }
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-family: 'Arial', 'Helvetica', sans-serif;
            background-color: #ffffff;
        }

        /* CERTIFICATE WRAPPER */
        .permit-wrapper {
            position: relative;
            width: 100%;
            background-color: #ffffff;
            padding: 20px;
        }

        /* EXPIRED WATERMARK */
        .expired-watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 120px;
            font-weight: 900;
            color: rgba(220, 53, 69, 0.15);
            letter-spacing: 10px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 1;
            width: 100%;
            text-align: center;
        }

        /* PHOTO GREYSCALE WHEN EXPIRED */
        .expired-photo {
            filter: grayscale(100%);
        }

        /* CONTAINER */
        .container {
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
            position: relative;
            z-index: 2;
        }

        /* HEADER */
        .header-section {
            background: linear-gradient(to right, #003366, #b30000) !important;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }

        .header-section .organization-name {
            font-weight: 700;
            font-size: 18px;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .header-section .organization-subtitle {
            font-size: 13px;
            opacity: 0.95;
        }

        /* CERTIFICATE TYPE */
        .certificate-type {
            color: #dc3545;
            font-weight: 700;
            margin-bottom: 15px;
            text-align: center;
            font-size: 16px;
        }

        /* TITLE */
        .certificate-title {
            font-weight: 700;
            font-size: 22px;
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }

        /* VERIFICATION TEXT */
        .verification-text {
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
            font-size: 15px;
            color: #333;
            line-height: 1.6;
        }

        /* MAIN CONTENT SECTION */
        .main-content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* INFO BOXES */
        .info-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 6px;
            background-color: #ffffff;
            margin-bottom: 15px;
            min-height: 100%;
        }

        .info-box-title {
            font-weight: 700;
            margin-bottom: 12px;
            font-size: 14px;
            color: #333;
            border-bottom: 2px solid #003366;
            padding-bottom: 8px;
        }

        .info-row {
            margin-bottom: 8px;
            font-size: 13px;
            line-height: 1.5;
            color: #333;
        }

        .info-row strong {
            font-weight: 700;
            color: #003366;
            min-width: 100px;
            display: inline-block;
        }

        .info-row div {
            margin-bottom: 4px;
        }

        /* PHOTO BOX */
        .photo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 0;
        }

        .photo-box {
            border: 2px solid #003366;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-radius: 6px;
            width: 140px;
            height: 140px;
            margin: 0 auto;
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-box-label {
            font-size: 12px;
            color: #666;
            font-weight: 600;
            margin-top: 8px;
            text-align: center;
        }

        /* TEST RESULTS SCORE BADGE */
        .score-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #28a745;
            color: white;
            font-weight: 700;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
        }

        /* VALIDITY MESSAGE */
        .validity-section {
            text-align: center;
            font-weight: 700;
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
            padding: 15px;
            margin: 20px 0;
            font-size: 16px;
            color: #333;
            background-color: #f9f9f9;
        }

        /* EXPIRED MESSAGE */
        .expired-message {
            text-align: center;
            font-weight: 700;
            border-top: 2px solid #ddd;
            border-bottom: 2px solid #ddd;
            padding: 15px;
            margin: 15px 0;
            font-size: 16px;
            color: #dc3545;
            background-color: #fff5f5;
        }

        /* CONCLUSION TEXT */
        .conclusion-section {
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            line-height: 1.8;
            color: #333;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 6px;
        }

        .conclusion-section strong {
            font-weight: 700;
            color: #003366;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .info-box {
                margin-bottom: 15px;
            }

            .certificate-title {
                font-size: 18px;
            }

            .header-section .organization-name {
                font-size: 16px;
            }

            .info-row strong {
                display: block;
                margin-bottom: 4px;
            }
        }

        /* PDF OPTIMIZATION */
        @media print {
            html,
            body {
                background-color: white !important;
            }

            .permit-wrapper {
                box-shadow: none;
                padding: 0;
            }

            .container {
                max-width: 100% !important;
            }

            .info-box {
                page-break-inside: avoid;
            }

            .row {
                display: table !important;
                width: 100% !important;
            }

            .col-md-2,
            .col-md-3 {
                display: table-cell !important;
                vertical-align: top !important;
            }

            .col-md-2 {
                width: 20% !important;
            }

            .col-md-3 {
                width: 33.333% !important;
                padding: 10px !important;
            }
        }
    </style>
</head>

<body>

    <div class="permit-wrapper">

        {{-- WATERMARK (if expired) --}}
        @if ($isExpired ?? false)
            <div class="expired-watermark">EXPIRED</div>
        @endif

        <div class="container">

            <!-- HEADER -->
            <div class="header-section">
                <div class="organization-name">
                    SOUTH EAST REGIONAL HEALTH AUTHORITY
                </div>
                <div class="organization-subtitle">
                    Public Health Certificate Management System - Verification by IDPro
                </div>
            </div>

            <!-- CERTIFICATE TYPE -->
            <div class="certificate-type">
                @if ($applicant['sign_offs'] && $applicant['sign_offs']['is_granted'])
                    Official Food Handlers Permit Certificate
                @else
                    Confirmation of Application for Food Handlers Permit
                @endif
            </div>

            <!-- TITLE -->
            <div class="certificate-title">
                Verification
            </div>

            <!-- VERIFICATION TEXT -->
            <div class="verification-text">
                @if ($applicant['sign_offs'] && $applicant['sign_offs']['is_granted'])
                    This confirms the issuance of the Food Handlers Permit for:
                @else
                    This confirms the application for a Food Handlers Permit for:
                @endif
            </div>

            <!-- MAIN CONTENT -->
            <div class="main-content">
                <div class="row g-3 align-items-start">

                    <!-- PHOTO -->
                    <div class="col-md-2 photo-container">
                        <div>
                            <div class="photo-box">
                                @if ($applicant['photo_upload'] ?? false)
                                    <img src="{{ asset('storage/' . $applicant['photo_upload']) }}"
                                        class="{{ ($isExpired ?? false) ? 'expired-photo' : '' }}"
                                        alt="Applicant Photo">
                                @else
                                    <div style="text-align: center; color: #999; font-size: 12px;">
                                        Photo Not Available
                                    </div>
                                @endif
                            </div>
                            <div class="photo-box-label">Photo</div>
                        </div>
                    </div>

                    <!-- APPLICANT DETAILS -->
                    <div class="col-md-3">
                        <div class="info-box">
                            <div class="info-box-title">Applicant Details</div>
                            <div class="info-row">
                                <strong>Name:</strong>
                                <div>{{ strtoupper($applicant['lastname'] ?? 'N/A') }}, {{ strtoupper($applicant['firstname'] ?? 'N/A') }}</div>
                            </div>
                            <div class="info-row">
                                <strong>DOB:</strong>
                                <div>{{ isset($applicant['date_of_birth']) ? date('d M Y', strtotime($applicant['date_of_birth'])) : 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <strong>Permit #:</strong>
                                <div>{{ $applicant['permit_no'] ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <strong>Category:</strong>
                                <div>{{ $applicant['permit_category']['name'] ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <strong>App. Date:</strong>
                                <div>{{ isset($applicant['application_date']) ? date('d M Y', strtotime($applicant['application_date'])) : 'No Application Date' }}</div>
                            </div>
                            <div class="info-row">
                                <strong>Issued:</strong>
                                <div>{{ isset($applicant['sign_offs']['sign_off_date']) ? date('d M Y', strtotime($applicant['sign_offs']['sign_off_date'])) : 'Pending' }}</div>
                            </div>
                            <div class="info-row">
                                <strong>Expiry:</strong>
                                <div>{{ isset($applicant['sign_offs']['expiry_date']) ? date('d M Y', strtotime($applicant['sign_offs']['expiry_date'])) : 'Pending' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- PERSONAL INFORMATION -->
                    <div class="col-md-3">
                        <div class="info-box">
                            <div class="info-box-title">Personal Information</div>
                            <div class="info-row">
                                <strong>Address:</strong>
                                <div>{{ strtoupper($applicant['address'] ?? 'No Address') }}</div>
                            </div>
                            <div class="info-row">
                                <strong>Gender:</strong>
                                <div>{{ ucfirst($applicant['gender'] ?? 'No Gender') }}</div>
                            </div>
                            <div class="info-row">
                                <strong>Occupation:</strong>
                                <div>{{ $applicant['occupation'] ?? 'No Occupation Given' }}</div>
                            </div>
                            <div class="info-row">
                                <strong>Employer:</strong>
                                <div>{{ $applicant['employer'] ?? 'N/A' }}</div>
                            </div>
                            <div class="info-row">
                                <strong>Employer Address:</strong>
                                <div>{{ $applicant['employer_address'] ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- TEST RESULTS -->
                    @if ($applicant['test_results'] ?? false)
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-title">Test Results</div>
                                <div class="info-row">
                                    <strong>Location:</strong>
                                    <div>{{ $applicant['test_results']['test_location'] ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <strong>Contact:</strong>
                                    <div>{{ $applicant['test_results']['staff_contact'] ?? 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <strong>Date:</strong>
                                    <div>{{ isset($applicant['test_results']['test_date']) ? date('d M Y', strtotime($applicant['test_results']['test_date'])) : 'N/A' }}</div>
                                </div>
                                <div class="info-row">
                                    <strong>Score:</strong>
                                    <div>
                                        <span class="score-badge">
                                            {{ $applicant['test_results']['overall_score'] ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-title">Test Results</div>
                                <div class="info-row">
                                    <div style="color: #999; font-style: italic;">No Test Results Available</div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            <!-- VALIDITY MESSAGE -->
            <div class="validity-section">
                @if (empty($applicant['sign_offs']))
                    THIS IS NOT A VALID FOOD HANDLERS PERMIT.
                @else
                    THIS IS AN OFFICIAL FOOD HANDLERS E-CARD.
                @endif
            </div>

            <!-- EXPIRED MESSAGE -->
            @if ($isExpired ?? false)
                <div class="expired-message">
                    YOUR FOOD HANDLERS PERMIT HAS EXPIRED.
                </div>
            @endif

            <!-- CONCLUSION -->
            <div class="conclusion-section">
                @if (empty($applicant['sign_offs']))
                    To finalize this application, the applicant must complete the Food Handlers examination and attend
                    the Medical Interview. The appointment date is
                    <strong>{{ isset($applicant['appointment'][0]['appointment_date']) ? date('d F Y', strtotime($applicant['appointment'][0]['appointment_date'])) : 'No Date Scheduled' }}</strong>.
                    After successful completion, the Medical Officer of Health will review the results and, if approved,
                    officially sign off on the application. Once signed, the permit becomes an Official Food Handlers
                    Permit in accordance with the requirements of the Food Safety Act (1998), which mandates medical
                    clearance and certification for all persons involved in the handling and preparation of food.
                @else
                    The application has now been reviewed and approved by the <strong>Medical Officer of Health (MOH)</strong>.
                    In accordance with the <strong>Food Safety Act (1998)</strong>, individuals who handle, prepare, or come
                    into contact with food for public consumption must be medically examined, certified, and officially
                    authorized before engaging in food-handling activities. With the successful completion of the required
                    examination and medical interview, and the formal sign-off granted, this applicant is now legally
                    recognized as certified to handle food and may operate in compliance with national public health
                    regulations.
                @endif
            </div>

        </div>

    </div>

</body>

</html>