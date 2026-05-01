<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">

    <!-- Responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Title -->
    <title>Food Handlers Permit Confirmation</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Better font rendering -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <!-- Print / PDF Optimization -->
    <style>
        @page {
            margin: 15mm;
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>

<body class="bg-light">

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

        <!-- SUB HEADER -->
        <div class="text-danger fw-bold mb-2">
            @if ($applicant->signOffs && $applicant->signOffs->is_granted)
                Official Food Handlers Permit Certificate
            @else
                Confirmation of Application for Food Handlers Permit
            @endif
        </div>

        <!-- BARCODE -->
        {{-- <div class="text-end mb-3">
            <div class="fw-bold fs-5" style="letter-spacing:2px;">
                ||| | ||||| || ||| ||||
            </div>
            <div class="small">
                {{ $applicant->permit_no }}
            </div>
        </div> --}}

        <!-- TITLE -->
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
                <div class="border bg-light d-flex align-items-center justify-content-center rounded-0"
                    style="width:120px;height:120px;overflow:hidden;margin:auto;">
                    @if ($applicant->photo_upload)
                        <img src="{{ asset('storage/' . $applicant->photo_upload) }}"
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

                    <div>Name: {{ strtoupper($applicant->lastname) }}, {{ strtoupper($applicant->firstname) }}</div>
                    <div>DOB: {{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('d M Y') }}</div>
                    <div>Permit: {{ $applicant->permit_no }}</div>
                    <div>Category: {{ $applicant->permitCategory->name ?? 'N/A' }}</div>

                    <div>Issued:
                        {{ optional($applicant->signOffs)->sign_off_date
                            ? \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y')
                            : 'Pending' }}
                    </div>

                    <div>Expiry:
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

                    <div>Address: {{ strtoupper($applicant->address) ?? 'No Address' }}</div>
                    <div>Gender: {{ Str::ucfirst($applicant->gender) ?? 'No Gender' }}</div>
                    <div>Occupation: {{ $applicant->occupation ?? 'No Occupation Given' }}</div>

                </div>
            </div>

            @if ($applicant->testResults)
                <div class="col-md-3">
                    <div class="border p-2 rounded bg-white small">

                        <div class="fw-bold mb-1">Test Results</div>

                        <div>Location: {{ $applicant->testResults->test_location ?? 'N/A' }}</div>
                        <div>Contact: {{ $applicant->testResults->staff_contact ?? 'N/A' }}</div>

                        <div>
                            Date:
                            {{ $applicant->testResults->test_date
                                ? \Carbon\Carbon::parse($applicant->testResults->test_date)->format('d M Y')
                                : 'N/A' }}
                        </div>

                        <div
                            class="d-flex align-items-center justify-content-between border rounded px-2 py-1 mt-2 bg-light">

                            <div class="fw-bold small text-muted">
                                Score
                            </div>

                            <div class="px-2 py-1 rounded text-white fw-bold bg-success">
                                {{ $applicant->testResults->overall_score ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-md-3">
                    No Test Results are available
                </div>
            @endif

        </div>

        <!-- WARNING -->
        <div class="text-center fw-bold border-top border-bottom py-2 my-4">

            @if (empty($applicant->signOffs))
                THIS IS NOT A VALID FOOD HANDLERS PERMIT.
            @else
                THIS IS AN OFFICIAL FOOD HANDLERS E-CARD.
            @endif

        </div>




        <div class="mt-2">
            @if (empty($applicant->signOffs))
                To finalize this application, the applicant must complete the Food Handlers examination and attend the
                Medical Interview.
                The appointment date is
                <strong>{{ optional($applicant->appointment[0])->appointment_date ? \Carbon\Carbon::parse($applicant->appointment[0]->appointment_date)->format('d F Y') : 'No Date Scheduled' }}</strong>.
                After successful completion, the Medical Officer of Health will review the results and, if approved,
                officially sign off on the application.
                Once signed, the permit becomes an Official Food Handlers Permit in accordance with the requirements of
                the Food Safety Act (1998),
                which mandates medical clearance and certification for all persons involved in the handling and
                preparation of food.
            @else
                The application has now been reviewed and approved by the <strong>Medical Officer of Health(MOH)</strong>.
                In accordance with the <strong>Food Safety Act (1998)</strong>, individuals who handle, prepare, or come into contact
                with food for public consumption must be medically examined, certified, and officially authorized before
                engaging in food-handling activities.
                With the successful completion of the required examination and medical interview, and the formal
                sign-off granted, this applicant is now legally recognized as certified to handle food and may operate
                in compliance with national public health regulations.
            @endif
        </div>



    </div>

</body>

</html>
