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

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            background: #ffffff; /* Better for PDF rendering */
            padding: 15px;
            color: #333;
        }

        .card {
            width: 100%;
            max-width: 720px;
            margin: auto;
            border-radius: 18px;
            padding: 10px 20px;
            border: 1px solid var(--border);
            page-break-inside: avoid;
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
            background: #e9f1fb;
            padding: 10px 14px;
            border-radius: 8px;
            border-left: 5px solid var(--primary);
            font-size: 13px;
            margin-bottom: 8px; /* Replaces grid gap */
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
    </style>
</head>

<body>
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
                            <td class="value">{{ $applicant->permitCategory->name }}</td>
                        </tr>
                        <tr>
                            <td class="label">Name:</td>
                            <td class="value">{{ strtoupper($applicant->lastname) }}, {{ strtoupper($applicant->firstname) }}</td>
                        </tr>
                        <tr>
                            <td class="label">Permit #:</td>
                            <td class="value">{{ $applicant->permit_no }}</td>
                        </tr>
                        <tr>
                            <td class="label">Issued:</td>
                            <td class="value">
                                {{ \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Expires:</td>
                            <td class="value" style="color:#d9534f">
                                {{ \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y') }}
                            </td>
                        </tr>
                    </table>
                </td>

                <td width="5%"></td>

                <td width="20%" align="right">
                    <div class="photo-wrapper">
                        <img src="{{ public_path('storage/' . $applicant->photo_upload) }}">
                    </div>
                </td>
            </tr>
        </table>

        <div class="section-title">MEDICAL TEST RESULTS</div>

        <div class="results">
            <div class="test"><b>Medical Exam(Whitlow):</b> {{ $applicant->healthInterviews?->whitlow ?? "No Medical Informatio" }}</div>
            <div class="test"><b>Test Results:</b> {{ $applicant->testResults?->overall_score ?? "No Score" }}</div>
            <div class="test"><b>Test Date:</b>{{ \Carbon\Carbon::parse($applicant->test_date)->format('d F Y') }}</div>
            <div class="test"><b>Test Location:</b>{{ $application->test_location ?? "No Exam Location"}}</div>

        </div>

        <div class="approval">
            <span class="badge">OFFICIALLY VERIFIED</span><br>
            This applicant has successfully completed all required medical examinations
            and has been approved by the Medical Officer of Health. The holder is legally
            certified to handle food in accordance with national public health regulations.
        </div>

    </div>
</body>

</html>