

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
            /* border-left: 5px solid #0b4ea2; */
            font-size: 13px;
            margin-bottom: 8px;
        }

        .approval {
            margin-top: 18px;
            background: #f4f8fc;
            /* border-left: 6px solid #1ea44c; */
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
            /* border-top: 1px solid #d9dee7; */
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
        <div class="section-title">TRAINING AND MEDICAL CLEARANCE RESULTS</div>

        <div class="test"><b>Medical Clearance:</b> {{ $applicant->healthInterviews?->whitlow === "absent" ?? 'Passed' : 'Failed' }}</div>
        <div class="test"><b>Food Handling Training:</b> {{ $applicant->testResults?->overall_score > 75 ?? "Passed" : "Failed"}}</div>
        <div class="test"><b>Test Date:</b> {{ $applicant->testResults?->test_date ? \Carbon\Carbon::parse($applicant->testResults->test_date)->format('d F Y') : 'N/A' }}</div>
        <div class="test"><b>Test Location:</b> {{ $applicant->testResults?->test_location ?? 'No Exam Location' }}</div>

        <div class="approval">
            <span class="badge">OFFICIALLY VERIFIED</span><br>
            This applicant has successfully completed all required medical examinations
            and has been approved by the Medical Officer of Health.
        </div>
    </div>

    <div style="text-align:center; margin-top:20px;">
    <img src="data:image/png;base64,{{ $qrImage }}" width="120">

    <div style="font-size:10px; margin-top:5px;">
        Scan to verify permit
    </div>
    <div style="margin-top: 15px; font-size: 10px; color: #666; text-align: justify; line-height: 1.4; border-top: 1px solid #ddd; padding-top: 10px;">
    <strong>Data Protection Notice:</strong> This document complies with the Jamaica Data Protection Act (2020). Sensitive medical data has been minimized to protect applicant privacy while fulfilling the regulatory requirements of the Food Safety Act (1998).
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