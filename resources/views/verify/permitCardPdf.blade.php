{{-- <!DOCTYPE html>
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

        body {
            font-family: Helvetica, Arial, sans-serif;
            background: #ffffff;
            padding: 15px;
            color: #333;
            /* Required for absolutely positioned watermark */
            position: relative;
        }

      .watermark-overlay {
    position: fixed;            /* stays centered on page */
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 420px;               /* adjust size if needed */
    opacity: 0.08;              /* faded look */
    z-index: 999;               /* ABOVE everything */
    pointer-events: none;       /* ignore clicks / layout */
}

        .card {
            width: 100%;
            max-width: 720px;
            margin: auto;
            border-radius: 18px;
            padding: 10px 20px;
            border: 1px solid var(--border);
            page-break-inside: avoid;
            /* Transparent background so watermark shows through */
            background-color: transparent;
        }

        /* --- PDF SAFE LAYOUT UTILS --- */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
        }

        /* HEADER */
        .header-table {
            border-bottom: 3px solid var(--primary);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-img {
            height: 70px;
        }

        .title h1 {
            margin: 0;
            color: var(--primary);
            font-size: 18px;
            letter-spacing: 1px;
            text-align: center;
        }

        .title small {
            font-size: 11px;
            color: #666;
            display: block;
            text-align: center;
            margin-top: 4px;
        }

        /* DETAILS TABLE */
        .details-table td {
            padding: 10px 0;
            border-bottom: 1px dashed #e6e6e6;
        }

        .label {
            font-weight: bold;
            color: var(--primary);
            width: 120px;
        }

        .value {
            font-weight: 600;

        }

        /* PHOTO */
        .photo-wrapper {
            width: 140px;
            height: 140px;
            border-radius: 14px;
            border: 2px solid #cfcfcf;
            overflow: hidden;
            text-align: center;
            background: #fff;
            /* Ensure photo background isn't transparent */
        }

        .photo-wrapper img {
            width: 140px;
            height: 140px;
            object-fit: cover;
        }

        /* SECTION TITLES */
        .section-title {
            margin-top: 25px;
            margin-bottom: 12px;
            font-size: 13px;
            font-weight: bold;
            color: var(--primary);
            border-bottom: 2px solid var(--primary);
            padding-bottom: 5px;
        }

        /* TEST RESULTS */
        .test {
            /* Make background slightly transparent so watermark shows */
            background: rgba(233, 241, 251, 0.8);
            padding: 10px 14px;
            border-radius: 8px;
            border-left: 5px solid var(--primary);
            font-size: 13px;
            margin-bottom: 8px;
        }

        /* APPROVAL */
        .approval {
            margin-top: 18px;
            /* Make background slightly transparent */
            background: rgba(244, 248, 252, 0.85);
            border-left: 6px solid #1ea44c;
            padding: 16px;
            border-radius: 8px;
            font-size: 13px;
            page-break-inside: avoid;
            line-height: 1.5;
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

        .footer {
            margin-top: 28px;
            padding-top: 18px;
            border-top: 2px solid #e1e6ef;
            page-break-inside: avoid;
        }

        .verify-grid {
            display: grid;
            grid-template-columns: 140px 1fr 160px;
            align-items: end;
            gap: 20px;
        }

        /* QR AREA */
        .qr-box {
            text-align: center;
            font-size: 11px;
        }

        .qr-box img {
            width: 110px;
            height: 110px;
        }

        /* SIGNATURE AREA */
        .signature {
            text-align: center;
        }

        .signature img {
            height: 60px;
            margin-bottom: 6px;
        }

        .sig-line {
            border-top: 1px solid #333;
            width: 220px;
            margin: 6px auto 4px;
        }

        .sig-title {
            font-size: 12px;
            font-weight: bold;
            color: #0b4ea2;
        }

        .sig-sub {
            font-size: 11px;
            color: #555;
        }

        /* STAMP AREA */
        .stamp {
            text-align: center;
        }

        .stamp img {
            width: 130px;
            opacity: 0.85;
        }

        .stamp small {
            display: block;
            font-size: 11px;
            margin-top: 6px;
        }

        .card, .footer {
    position: relative;
    z-index: 1;
}

.document-footer {
    width: 100%;
    margin-top: 30px;
    padding-top: 10px;
    border-top: 1px solid #d9dee7;
    
    display: table;          /* PDF-safe centering trick */
    table-layout: fixed;
    font-size: 10px;         /* smaller text */
    color: #666;
}

.footer-item {
    display: table-cell;
    text-align: center;      /* evenly spaced */
}
    </style>
</head>

<body>
    <!-- FOREGROUND WATERMARK OVERLAY -->
<img src="{{ public_path('images/serha_logo.png') }}" class="watermark-overlay">

    <div class="card">
        <table class="header-table">
            <tr>
                <td width="20%" align="left">
                    <img src="{{ public_path('images/coatofarms.png') }}" class="header-img">
                </td>
                <td width="60%" class="title">
                    <h1>MINISTRY OF HEALTH & WELLNESS</h1>
                    <small>Public Health (Food Handling 1998) Regulations 26–31</small>
                </td>
                <td width="20%" align="right">
                    <img src="{{ public_path('images/mohlogo.png') }}" class="header-img">
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="75%">
                    <table class="details-table">
                        <tr>
                            <td class="label">Category:</td>
                            <td class="value">{{ $applicant->permitCategory->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Name:</td>
                            <td class="value">{{ strtoupper($applicant->lastname ?? '') }},
                                {{ strtoupper($applicant->firstname ?? '') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Permit #:</td>
                            <td class="value">{{ $applicant->permit_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Issued:</td>
                            <td class="value">
                                {{ optional($applicant->signOffs)->sign_off_date ? \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y') : 'N/A' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Expires:</td>
                            <td class="value" style="color:#d9534f">
                                {{ optional($applicant->signOffs)->expiry_date ? \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y') : 'N/A' }}
                            </td>
                        </tr>
                    </table>
                </td>

                <td width="5%"></td>

                <td width="20%" align="right">
                    <div class="photo-wrapper">
                        @if ($applicant->photo_upload)
                            <img src="{{ public_path('storage/' . $applicant->photo_upload) }}">
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">MEDICAL TEST RESULTS</div>

        <div class="results">
            <div class="test"><b>Medical Exam(Whitlow):</b>
                {{ Str::ucfirst($applicant->healthInterviews?->whitlow) ?? 'No Medical Information' }}</div>
            <div class="test"><b>Test Results:</b> {{ $applicant->testResults?->overall_score ?? 'No Score' }}</div>
            <div class="test"><b>Test Date:</b>
                {{ \Carbon\Carbon::parse($applicant->testResults?->test_date)->format('d F Y') ?? 'N/A' }}
            </div>
            <div class="test"><b>Test Location:</b> {{ $applicant->testResults?->test_location ?? 'No Exam Location' }}</div>
        </div>

        <div class="approval">
            <span class="badge">OFFICIALLY VERIFIED</span><br>
            This applicant has successfully completed all required medical examinations
            and has been approved by the Medical Officer of Health. The holder is legally
            certified to handle food in accordance with national public health regulations.
        </div>
    </div>

   <footer class="document-footer">
    <div class="footer-item">
        South East Regional Health Authority
    </div>

    <div class="footer-item">
        Application #: {{ $applicant->id ?? 'No Application Number' }}
    </div>

    <div class="footer-item">
        {{ \Carbon\Carbon::now()->format('d M Y • h:i A') }}
    </div>
</footer>
</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #ffffff;
            padding: 15px;
            color: #222;
            position: relative;
        }

        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }

        /* ===== WATERMARK ===== */
        .watermark {
            position: fixed;
            top: 45%;
            left: 50%;
            width: 420px;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            z-index: -1;
        }

        /* ===== ID CARD ===== */
        .id-card {
            background-color: #fdfdfd;
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            padding: 20px 25px;
            max-width: 400px;
            margin: 0 auto;
            /* border-bottom: 3px solid #ccc; */
        }

        .card-header td {
            vertical-align: middle;
            padding-bottom: 20px;
        }

        .card-title {
            margin: 0;
            font-size: 16px;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #111;
        }

        .card-subtitle {
            margin: 4px 0 0 0;
            font-size: 10px;
            color: #555;
            line-height: 1.4;
        }

        .card-details td {
            padding: 6px 0;
            font-size: 14px;
        }

        .card-label {
            font-weight: bold;
            width: 90px;
        }

        .card-photo {
            width: 120px;
            height: 130px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        /* ===== EXTRA SECTIONS ===== */
        .extra-sections {
            max-width: 650px;
            margin: 30px auto 0 auto;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            color: #0b4ea2;
            border-bottom: 2px solid #0b4ea2;
            padding-bottom: 5px;
            margin-bottom: 12px;
        }

        .test {
            background: #e9f1fb;
            padding: 10px 14px;
            border-radius: 8px;
            border-left: 5px solid #0b4ea2;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .approval {
            margin-top: 18px;
            background: #f4f8fc;
            border-left: 6px solid #1ea44c;
            padding: 16px;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.5;
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

        /* ===== FOOTER ===== */
        .document-footer {
            width: 100%;
            margin-top: 35px;
            padding-top: 10px;
            border-top: 1px solid #d9dee7;
            display: table;
            table-layout: fixed;
            font-size: 10px;
            color: #666;
        }

        .footer-item {
            display: table-cell;
            text-align: center;
        }
    </style>
</head>

<body>

    <!-- WATERMARK -->
    {{-- <img src="{{ public_path('images/serha_logo.png') }}" class="watermark"> --}}

    <!-- ID CARD -->
    <div class="id-card">
        <table class="card-header">
            <tr>
                <td width="15%">
                    <img src="{{ public_path('images/coatofarms.png') }}" style="height:55px;">
                </td>
                <td width="70%" align="center">
                    <h1 class="card-title">MIN. OF HEALTH AND WELLNESS</h1>
                    <p class="card-subtitle">
                        Public Health (Food Handling 1998) Regulations<br>
                        26,27,28,29,30 & 31
                    </p>
                </td>
                <td width="15%" align="right">
                    <img src="{{ public_path('images/mohlogo.png') }}" style="height:45px;">
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="65%">
                    <table class="card-details">
                        <tr>
                            <td class="card-label">Category:</td>
                            <td>{{ $applicant->permitCategory->name ?? 'Basic Foodhandlers' }}</td>
                        </tr>
                        <tr>
                            <td class="card-label">Name:</td>
                            <td>{{ strtoupper($applicant->lastname) }}, {{ strtoupper($applicant->firstname) }}</td>
                        </tr>
                        <tr>
                            <td class="card-label">Permit#:</td>
                            <td>{{ $applicant->permit_no }}</td>
                        </tr>
                        <tr>
                            <td class="card-label">Issued:</td>
                            <td>{{ optional($applicant->signOffs)->sign_off_date ? \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y') : 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="card-label">Expires:</td>
                            <td>{{ optional($applicant->signOffs)->expiry_date ? \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y') : 'N/A' }}</td>
                        </tr>
                    </table>
                </td>
                <td width="35%" align="right">
                    @if ($applicant->photo_upload)
                        <img src="{{ public_path('storage/' . $applicant->photo_upload) }}" class="card-photo">
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <!-- MEDICAL RESULTS -->
    <div class="extra-sections">
        <div class="section-title">MEDICAL TEST RESULTS</div>

        <div class="test"><b>Medical Exam (Whitlow):</b> {{ Str::ucfirst($applicant->healthInterviews?->whitlow ?? 'No Medical Information') }}</div>
        <div class="test"><b>Test Results:</b> {{ $applicant->testResults?->overall_score ?? 'No Score' }}</div>
        <div class="test"><b>Test Date:</b> {{ $applicant->testResults?->test_date ? \Carbon\Carbon::parse($applicant->testResults->test_date)->format('d F Y') : 'N/A' }}</div>
        <div class="test"><b>Test Location:</b> {{ $applicant->testResults?->test_location ?? 'No Exam Location' }}</div>

        <div class="approval">
            <span class="badge">OFFICIALLY VERIFIED</span><br>
            This applicant has successfully completed all required medical examinations
            and has been approved by the Medical Officer of Health.
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="document-footer">
        
        <div class="footer-item"><img src="{{ public_path('images/serha_logo.png') }}" style="width:0.75rem"> South East Regional Health Authority</div>
        <div class="footer-item">Application #: {{ $applicant->id ?? 'N/A' }}</div>
        <div class="footer-item">{{ \Carbon\Carbon::now()->format('d M Y • h:i A') }}</div>
    </footer>

</body>
</html>