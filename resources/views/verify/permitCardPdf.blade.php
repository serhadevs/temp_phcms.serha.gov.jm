<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        :root {
            --primary: #0b4ea2;
            --light: #f3f7fc;
            --border: #d9dee7;
            --text: #333;
        }

        /* IMPORTANT FOR PDF */
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            background: #eef1f5;
            padding: 15px;
        }

        .card {
            width: 100%;
            max-width: 720px;
            margin: auto;
            background: #fff;
            border-radius: 18px;
            padding: 28px 32px;
            border: 1px solid var(--border);
            page-break-inside: avoid;
        }

        /* HEADER PERFECT ALIGNMENT */
        .header {
            display: grid;
            grid-template-columns: 90px 1fr 120px;
            align-items: center;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 15px;
        }

        .header img {
            height: 70px;
        }

        .title {
            text-align: center;
        }

        .title h1 {
            margin: 0;
            color: var(--primary);
            font-size: 20px;
            letter-spacing: 1px;
        }

        .title small {
            font-size: 11px;
            color: #666;
        }

        /* MAIN TWO COLUMN LAYOUT */
        .main {
            display: grid;
            grid-template-columns: 1fr 150px;
            gap: 25px;
            margin-top: 20px;
        }

        /* DETAILS */
        .row {
            display: grid;
            grid-template-columns: 120px 1fr;
            padding: 10px 0;
            border-bottom: 1px dashed #e6e6e6;
        }

        .label {
            font-weight: bold;
            color: var(--primary);
        }

        .value {
            font-weight: 600;
        }

        /* PHOTO FIX */
        .photo {
            width: 140px;
            height: 140px;
            border-radius: 14px;
            border: 2px solid #cfcfcf;
            overflow: hidden;
        }

        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* SECTION TITLES */
        .section-title {
            margin-top: 22px;
            font-size: 13px;
            font-weight: bold;
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            padding-bottom: 5px;
        }

        /* TEST RESULTS */
        .results {
            margin-top: 12px;
            display: grid;
            gap: 8px;
        }

        .test {
            background: #e9f1fb;
            padding: 10px 14px;
            border-radius: 8px;
            border-left: 5px solid var(--primary);
            font-size: 13px;
        }

        /* APPROVAL */
        .approval {
            margin-top: 18px;
            background: #f4f8fc;
            border-left: 6px solid #1ea44c;
            padding: 16px;
            border-radius: 8px;
            font-size: 13px;
            page-break-inside: avoid;
        }

        .badge {
            background: #1ea44c;
            color: #fff;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
            margin-bottom: 8px;
        }
    </style>
</head>

<body>
    <div class="card">

        <div class="header">
            <img src="{{ public_path('images/coatofarms.png') }}">

            <div class="title">
                <h1>MINISTRY OF HEALTH & WELLNESS</h1>
                <small>Public Health (Food Handling 1998) Regulations 26–31</small>
            </div>

            <img src="{{ public_path('images/mohlogo.png') }}">
        </div>

        <!-- BASIC DETAILS -->
        <div class="main">
            <div>
                <div class="row">
                    <div class="label">Category:</div>
                    <div class="value">{{ $applicant->permitCategory->name }}</div>
                </div>
                <div class="row">
                    <div class="label">Name:</div>
                    <div class="value">{{ strtoupper($applicant->lastname) }}, {{ strtoupper($applicant->firstname) }}
                    </div>
                </div>
                <div class="row">
                    <div class="label">Permit #:</div>
                    <div class="value">{{ $applicant->permit_no }}</div>
                </div>
                <div class="row">
                    <div class="label">Issued:</div>
                    <div class="value">
                        {{ \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y') }}</div>
                </div>
                <div class="row">
                    <div class="label">Expires:</div>
                    <div class="value" style="color:#d9534f">
                        {{ \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y') }}</div>
                </div>
            </div>

            <div class="photo">
                <img src="{{ public_path('storage/' . $applicant->photo_upload) }}">
            </div>
        </div>

        <!-- TEST RESULTS -->
        <div class="section-title">MEDICAL TEST RESULTS</div>

        <div class="results">
            <div class="test"><b>Medical Exam:</b> Passed</div>
            <div class="test"><b>Food Handler Training:</b> Completed</div>
            <div class="test"><b>Interview:</b> Approved</div>
            <div class="test"><b>Status:</b> Fit for Food Handling</div>
        </div>

        <!-- APPROVAL -->
        <div class="approval">
            <span class="badge">OFFICIALLY VERIFIED</span>
            This applicant has successfully completed all required medical examinations
            and has been approved by the Medical Officer of Health. The holder is legally
            certified to handle food in accordance with national public health regulations.
        </div>

        {{-- <!-- QR CODE -->
    <div class="qr">
        <img src="{{ public_path('images/qrcode.png') }}">
        <div>Scan to verify this permit</div>
    </div> --}}

    </div>
</body>

</html>
