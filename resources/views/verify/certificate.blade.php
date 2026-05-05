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
        }

        body {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            font-family: Arial, Helvetica, sans-serif;
        }

        /* CERTIFICATE WRAPPER */
        .permit-wrapper {
            position: relative;
        }

        /* EXPIRED WATERMARK */
        /* EXPIRED WATERMARK - FULL PAGE DIAGONAL */
        .expired-watermark {
            position: fixed;
            /* covers entire page */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);

            font-size: 180px;
            /* BIGGER */
            font-weight: 900;
            letter-spacing: 20px;
            white-space: nowrap;

            color: rgba(220, 53, 69, 0.12);
            /* lighter red */
            pointer-events: none;
            z-index: 0;
            /* behind content */
        }

        /* Ensure certificate content sits above watermark */
        .permit-wrapper {
            position: relative;
            z-index: 1;
        }

        /* PHOTO GREYSCALE WHEN EXPIRED */
        .expired-photo {
            filter: grayscale(100%);
        }

        /* WATERMARK LAYER */
        .watermark {
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
            opacity: 0.06;
            /* faint */
        }

        .watermark::before {
            content: "";
            position: absolute;
            inset: -50%;
            background-image: url("{{ asset('images/serha_logo.png') }}");
            background-repeat: repeat;
            background-size: 20px;
            transform: rotate(-35deg);
        }
    </style>
</head>

<body class="bg-light">

    @php
        $isExpired = session('permit_is_expired');
    @endphp
    {{-- <div class="watermark"></div> --}}
    <div class="permit-wrapper">

        {{-- WATERMARK --}}
        @if ($isExpired)
            <div class="expired-watermark">EXPIRED</div>
        @endif

        <div class="container py-4" style="max-width: 900px;">

            <!-- HEADER -->
            <div class="bg-primary text-white p-3 rounded mb-2"
                style="background: linear-gradient(to right, #003366, #b30000) !important;">

                <!-- flex-column on mobile, flex-md-row on desktop. Centered on mobile, left-aligned on desktop -->
                <div class="d-flex flex-column flex-md-row align-items-center gap-3 text-center text-md-start">

                    <!-- WHITE CIRCLE LOGO (Added flex-shrink-0 so it doesn't get squished) -->
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow flex-shrink-0"
                        style="width:65px; height:65px;">
                        <img src="{{ asset('images/serha_logo.png') }}" alt="SERHA Logo"
                            style="height:45px; width:auto;">
                    </div>

                    <!-- TEXT WRAPPER -->
                    <div>
                        <!-- Added lh-sm for better line spacing when text wraps on small screens -->
                        <div class="fw-bold fs-5 lh-sm mb-1">
                            SOUTH EAST REGIONAL HEALTH AUTHORITY
                        </div>
                        <div class="small" style="opacity: 0.9;">
                            Public Health Certificate Management System - Verification by IDPro
                        </div>
                    </div>

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
                                class="{{ $isExpired ? 'expired-photo' : '' }}"
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

                        <div><strong>Name:</strong> {{ strtoupper($applicant->lastname) }},
                            {{ strtoupper($applicant->firstname) }}</div>
                        <div><strong>DOB:</strong>
                            {{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('d M Y') }}</div>
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
                            <div> Date:
                                {{ $applicant->testResults->test_date ? \Carbon\Carbon::parse($applicant->testResults->test_date)->format('d M Y') : 'N/A' }}
                            </div>
                            <div
                                class="d-flex align-items-center justify-content-between border rounded px-2 py-1 mt-2 bg-light">
                                <div class="fw-bold small text-muted"> Score </div>
                                <div class="px-2 py-1 rounded text-white fw-bold bg-success">
                                    {{ $applicant->testResults->overall_score ?? 'N/A' }} </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="col-md-3"> No Test Results are available </div>
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

            <div class="mt-2">
                @if (empty($applicant->signOffs))
                    To finalize this application, the applicant must complete the Food Handlers examination and attend
                    the Medical Interview. The appointment date is
                    <strong>
                        {{ optional(optional($applicant->appointment)->first())->appointment_date
                            ? \Carbon\Carbon::parse($applicant->appointment->first()->appointment_date)->format('d F Y')
                            : 'No Date Scheduled' }}
                    </strong>.
                    After successful completion, the Medical Officer of Health will review the results and, if approved,
                    officially sign off on the application. Once signed, the permit becomes an Official Food Handlers
                    Permit in accordance with the requirements of the Food Safety Act (1998), which mandates medical
                    clearance and certification for all persons involved in the handling and preparation of food.
                @elseif (isset($isExpired) && $isExpired)
                    You need to make an appointment at your health department to renew your permit.
                @else
                    The application has now been reviewed and approved by the <strong>Medical Officer of
                        Health(MOH)</strong>. In accordance with the <strong>Food Safety Act (1998)</strong>,
                    individuals who handle, prepare, or come into contact with food for public consumption must be
                    medically examined, certified, and officially authorized before engaging in food-handling
                    activities. With the successful completion of the required examination and medical interview, and
                    the formal sign-off granted, this applicant is now legally recognized as certified to handle food
                    and may operate in compliance with national public health regulations.
                @endif
            </div>


            @if(!$isExpired)
            <div class="text-center mt-4 no-print">
                <a href="{{ URL::temporarySignedRoute('verify.download', now()->addMinutes(5), ['id' => $applicant->id]) }}"
                    class="btn btn-primary">
                    Download PDF
                </a>

                <button onclick="emailConfirmation()" class="btn btn-success me-2">
                    Email Confirmation
                </button>
            </div>


        </div>
    </div>

</body>
<script>
    function emailConfirmation() {
        const subject = "Food Handlers Permit Confirmation";
        const body = "Please find my verified Food Handlers Permit confirmation at:\n\n" + window.location.href;
        window.location.href = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    }
</script>

</html>
