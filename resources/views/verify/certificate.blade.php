<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Food Handlers Permit Confirmation</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        @page { margin: 15mm; }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* CERTIFICATE WRAPPER */
        .permit-wrapper { position: relative; }

        /* EXPIRED WATERMARK */
        .expired-watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 120px;
            font-weight: 900;
            color: rgba(220,53,69,0.15);
            letter-spacing: 10px;
            white-space: nowrap;
            pointer-events: none;
            z-index: 10;
        }

        .permit-wrapper * { position: relative; z-index: 2; }

        /* PHOTO GREYSCALE WHEN EXPIRED */
        .expired-photo {
            filter: grayscale(100%);
        }
    </style>
</head>

<body class="bg-light">

<div class="permit-wrapper">

    {{-- WATERMARK --}}
    @if($isExpired)
        <div class="expired-watermark">EXPIRED</div>
    @endif

    <div class="container py-4" style="max-width: 900px;">

        <!-- HEADER -->
        <div class="bg-primary text-white p-3 rounded mb-2"
             style="background: linear-gradient(to right, #003366, #b30000) !important;">
            <div class="fw-bold fs-5">
                SOUTH EAST REGIONAL HEALTH AUTHORITY
            </div>
            <div class="small">
                Public Health Certificate Management System - Verification by IDPro
            </div>
        </div>

        <div class="text-danger fw-bold mb-2">
            @if ($applicant->signOffs && $applicant->signOffs->is_granted)
                Official Food Handlers Permit Certificate
            @else
                Confirmation of Application for Food Handlers Permit
            @endif
        </div>

        <h3 class="fw-bold">Verification</h3>

        <div class="fw-bold mb-3">
            @if ($applicant->signOffs && $applicant->signOffs->is_granted)
                This confirms the issuance of the Food Handlers Permit for:
            @else
                This confirms the application for a Food Handlers Permit for:
            @endif
        </div>

        <!-- MAIN CONTENT -->
        <div class="row g-3 align-items-start">

            <!-- PHOTO -->
            <div class="col-md-2 text-center">
                <div class="border bg-light d-flex align-items-center justify-content-center rounded"
                     style="width:120px;height:120px;overflow:hidden;margin:auto;">
                    @if ($applicant->photo_upload)
                        <img src="{{ asset('storage/' . $applicant->photo_upload) }}"
                             class="{{ $isExpiry ? 'expired-photo' : '' }}"
                             style="width:100%;height:100%;object-fit:cover;">
                    @else
                        Photo Not Available
                    @endif
                </div>
            </div>

            <!-- COLUMN 1 -->
            <div class="col-md-3">
                <div class="border p-2 rounded bg-white small">
                    <div class="fw-bold mb-1">Applicant Details</div>

                    <div><strong>Name:</strong> {{ strtoupper($applicant->lastname) }}, {{ strtoupper($applicant->firstname) }}</div>
                    <div><strong>DOB:</strong> {{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('d M Y') }}</div>
                    <div><strong>Permit #:</strong>{{ $applicant->permit_no }}</div>
                    <div><strong>Category:</strong> {{ $applicant->permitCategory->name ?? 'N/A' }}</div>
                    <div><strong>Application Date:</strong>
                        {{ \Carbon\Carbon::parse($applicant->application_date)->format('d M Y') ?? 'No Application Date' }}
                    </div>

                    <div><strong>Issued:</strong>
                        {{ optional($applicant->signOffs)->sign_off_date
                            ? \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y')
                            : 'Pending' }}
                    </div>

                    <div><strong>Expiry:</strong>
                        {{ optional($applicant->signOffs)->expiry_date
                            ? \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y')
                            : 'Pending' }}
                    </div>
                </div>
            </div>

            <!-- COLUMN 2 -->
            <div class="col-md-3">
                <div class="border p-2 rounded bg-white small">
                    <div class="fw-bold mb-1">Personal Info</div>
                    <div><strong>Address:</strong> {{ strtoupper($applicant->address) ?? 'No Address' }}</div>
                    <div><strong>Gender:</strong> {{ Str::ucfirst($applicant->gender) ?? 'No Gender' }}</div>
                    <div><strong>Occupation:</strong> {{ $applicant->occupation ?? 'No Occupation Given' }}</div>
                </div>
            </div>

            <!-- TEST RESULTS -->
            @if ($applicant->testResults)
            <div class="col-md-3">
                <div class="border p-2 rounded bg-white small">
                    <div class="fw-bold mb-1">Test Results</div>
                    <div>Location: {{ $applicant->testResults->test_location ?? 'N/A' }}</div>
                    <div>Contact: {{ $applicant->testResults->staff_contact ?? 'N/A' }}</div>
                    <div>Date:
                        {{ $applicant->testResults->test_date
                            ? \Carbon\Carbon::parse($applicant->testResults->test_date)->format('d M Y')
                            : 'N/A' }}
                    </div>

                    <div class="d-flex align-items-center justify-content-between border rounded px-2 py-1 mt-2 bg-light">
                        <div class="fw-bold small text-muted">Score</div>
                        <div class="px-2 py-1 rounded text-white fw-bold bg-success">
                            {{ $applicant->testResults->overall_score ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>

        <!-- VALIDITY MESSAGE -->
        <div class="text-center fw-bold border-top border-bottom py-2 my-4">
            @if (empty($applicant->signOffs))
                THIS IS NOT A VALID FOOD HANDLERS PERMIT.
            @else
                THIS IS AN OFFICIAL FOOD HANDLERS E-CARD.
            @endif
        </div>

        <!-- EXPIRED MESSAGE -->
        @if ($isExpired)
        <div class="text-center fw-bold border-top border-bottom py-2 my-4 text-danger">
            YOUR FOOD HANDLERS PERMIT HAS EXPIRED.
        </div>
        @endif

    </div>
</div>

</body>
</html>