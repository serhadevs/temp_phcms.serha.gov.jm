<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Official Permit Certificate</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        /* A few custom tweaks to match the official Ministry blue */
        :root {
            --moh-blue: #0b4ea2;
        }
        .text-moh-blue { color: var(--moh-blue); }
        .border-moh-blue { border-bottom: 3px solid var(--moh-blue); }
        .border-left-moh { border-left: 5px solid var(--moh-blue) !important; }
        .bg-moh-light { background-color: #e9f1fb; }
        
        /* Ensure the photo box stays square */
        .photo-box {
            width: 140px;
            height: 140px;
            object-fit: cover;
        }
    </style>
</head>

<body class="bg-light py-4 py-md-5">

    <div class="container">
        <div class="card shadow-lg border-0 rounded-4 mx-auto" style="max-width: 750px;">
            <div class="card-body p-4 p-md-5">

                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center border-moh-blue pb-3 mb-4 gap-3 text-center text-md-start">
                    <img src="{{ asset('images/coatofarms.png') }}" alt="Coat of Arms" style="height: 70px;">
                    
                    <div class="text-center flex-grow-1 px-3">
                        <h1 class="h5 fw-bold text-moh-blue mb-1" style="letter-spacing: 1px;">MINISTRY OF HEALTH & WELLNESS</h1>
                        <small class="text-muted fw-semibold" style="font-size: 11px;">Public Health (Food Handling 1998) Regulations 26–31</small>
                    </div>

                    <img src="{{ asset('images/mohlogo.png') }}" alt="MOH Logo" style="height: 70px;">
                </div>

                <div class="row g-4 mb-5">
                    
                    <div class="col-md-8 order-2 order-md-1">
                        <div class="row mb-2 border-bottom border-light pb-2">
                            <div class="col-4 fw-bold text-moh-blue">Category:</div>
                            <div class="col-8 fw-semibold">{{ $applicant->permitCategory->name }}</div>
                        </div>
                        <div class="row mb-2 border-bottom border-light pb-2">
                            <div class="col-4 fw-bold text-moh-blue">Name:</div>
                            <div class="col-8 fw-semibold">{{ strtoupper($applicant->lastname) }}, {{ strtoupper($applicant->firstname) }}</div>
                        </div>
                        <div class="row mb-2 border-bottom border-light pb-2">
                            <div class="col-4 fw-bold text-moh-blue">Permit #:</div>
                            <div class="col-8 fw-semibold">{{ $applicant->permit_no }}</div>
                        </div>
                        <div class="row mb-2 border-bottom border-light pb-2">
                            <div class="col-4 fw-bold text-moh-blue">Issued:</div>
                            <div class="col-8 fw-semibold">
                                {{ \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="row mb-2 pb-2">
                            <div class="col-4 fw-bold text-moh-blue">Expires:</div>
                            <div class="col-8 fw-bold text-danger">
                                {{ \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 order-1 order-md-2 d-flex justify-content-center justify-content-md-end">
                        <div class="border border-2 border-secondary-subtle rounded-3 p-1">
                            <img src="{{ asset('storage/' . $applicant->photo_upload) }}" class="photo-box rounded-2" alt="Applicant Photo">
                        </div>
                    </div>

                </div>

                <h6 class="fw-bold text-moh-blue border-bottom border-2 border-primary pb-2 mb-3">MEDICAL TEST RESULTS</h6>
                
                <div class="row g-2 mb-4">
                    <div class="col-md-6">
                        <div class="bg-moh-light p-2 rounded-2 border-left-moh small">
                            <span class="fw-bold">Medical Exam:</span> Passed
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-moh-light p-2 rounded-2 border-left-moh small">
                            <span class="fw-bold">Training:</span> Completed
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-moh-light p-2 rounded-2 border-left-moh small">
                            <span class="fw-bold">Interview:</span> Approved
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-moh-light p-2 rounded-2 border-left-moh small">
                            <span class="fw-bold">Status:</span> Fit for Food Handling
                        </div>
                    </div>
                </div>

                <div class="alert alert-success border-0 border-start border-5 border-success rounded-3 shadow-sm">
                    <span class="badge bg-success mb-2 px-3 py-2 rounded-pill shadow-sm" style="letter-spacing: 1px;">✓ OFFICIALLY VERIFIED</span>
                    <p class="mb-0 small" style="line-height: 1.6;">
                        This applicant has successfully completed all required medical examinations
                        and has been approved by the Medical Officer of Health. The holder is legally
                        certified to handle food in accordance with national public health regulations.
                    </p>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>