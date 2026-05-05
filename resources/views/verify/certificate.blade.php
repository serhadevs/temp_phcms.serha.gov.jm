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


        .expired-watermark {
            position: fixed;
            inset: 0;
            /* cover whole page */
            display: flex;
            align-items: center;
            justify-content: center;

            font-size: 180px;
            font-weight: 900;
            letter-spacing: 20px;
            white-space: nowrap;

            color: rgba(220, 53, 69, 0.20);
            /* slightly stronger red */

            transform: rotate(-30deg);

            pointer-events: none;
            z-index: 9999;
        }

        .permit-wrapper {
            position: relative;
        }

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



            <div class="container py-4 py-md-5">
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 col-lg-7">

                        <div class="card shadow-lg border-0 rounded-5 p-3 p-md-4" style="background:#ffffff;">

                            <!-- Header Logos -->
                            <div class="row align-items-center text-center text-md-start mb-3">

                                <!-- Left logo -->
                                <div class="col-3 col-md-2">
                                    <img src="{{ asset('images/coatofarms.png') }}" class="img-fluid"
                                        style="max-width:45px;">
                                </div>

                                <!-- TEXT (now wider) -->
                                <div class="col-6 col-md-8 text-center">
                                    <h6 class="fw-bold mb-0">
                                        MIN. OF HEALTH AND WELLNESS
                                    </h6>

                                    <small class="text-muted d-block mt-1" style="font-size:11px;">
                                        Public Health (Food Handling 1998) Regulations 26,27,28,29,30 & 31
                                    </small>
                                </div>

                                <!-- Right logo -->
                                <div class="col-3 col-md-2 text-end">
                                    <img src="{{ asset('images/mohlogo.png') }}" class="img-fluid"
                                        style="max-width:80px;">
                                </div>

                            </div>

                            <div class="row align-items-center">

                                <!-- DETAILS -->
                                <div class="col-12 col-md-8 order-2 order-md-1">

                                    <p class="mb-2"><strong>Category:</strong> Basic Foodhandlers</p>

                                    <p class="mb-2">
                                        <strong>Name:</strong>
                                        {{ strtoupper($applicant->lastname) }},
                                        {{ strtoupper($applicant->firstname) }}
                                    </p>

                                    <p class="mb-2">
                                        <strong>Permit#:</strong>
                                        {{ $applicant->permit_no ?? 'No Permit Number' }}
                                    </p>

                                    <p class="mb-2">
                                        <strong>Issued:</strong>
                                        {{ optional($applicant->signOffs)->sign_off_date
                                            ? \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y')
                                            : 'Pending' }}
                                    </p>

                                    <p class="mb-3 mb-md-0">
                                        <strong>Expires:</strong>
                                        {{ optional($applicant->signOffs)->expiry_date
                                            ? \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y')
                                            : 'Pending' }}
                                    </p>

                                </div>

                                <!-- PHOTO -->
                                <div class="col-12 col-md-4 text-center text-md-end mb-3 mb-md-0 order-1 order-md-2">
                                    <div class="border bg-light d-inline-flex align-items-center justify-content-center rounded"
                                        style="width:120px;height:120px;overflow:hidden;">

                                        @if ($applicant->photo_upload)
                                            <img src="{{ asset('storage/' . $applicant->photo_upload) }}"
                                                class="{{ $isExpired ? 'expired-photo' : '' }}"
                                                style="width:100%;height:100%;object-fit:cover;">
                                        @else
                                            <small>Photo Not Available</small>
                                        @endif

                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <!-- VALIDITY MESSAGE -->
            <!-- VALIDITY MESSAGE -->
            <div class="text-center fw-bold border-top border-bottom py-2 my-4">
                @if (!$applicant->signOffs || $isExpired)
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


            @if ($applicant->signOffs && !$isExpired)
                <div class="text-center mt-4 no-print">
                    <a href="{{ URL::temporarySignedRoute('verify.download', now()->addMinutes(5), ['id' => $applicant->id]) }}"
                        class="btn btn-primary">
                        Download PDF
                    </a>

                    <button onclick="emailConfirmation()" class="btn btn-success me-2">
                        Email Confirmation
                    </button>
                </div>
            @endif


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
