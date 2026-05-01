<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Permit Confirmation</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
            margin: 0;
            padding: 25px;
        }

        /* HEADER */
        .header-banner {
            background: linear-gradient(to right, #003366, #b30000);
            color: white;
            padding: 18px;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .header-banner span {
            font-size: 13px;
            display: block;
            margin-top: 5px;
            font-weight: normal;
        }

        .sub-header {
            color: #b30000;
            font-size: 14px;
            margin-bottom: 10px;
        }

        h1 {
            font-size: 26px;
            margin: 10px 0 15px;
        }

        .intro-text {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 15px;
        }

        /* BARCODE */
        .barcode-box {
            text-align: right;
            margin-bottom: 15px;
        }

        .barcode-lines {
            font-family: 'Courier New', monospace;
            font-size: 20px;
            letter-spacing: -1px;
            font-weight: bold;
        }

        .barcode-text {
            font-size: 12px;
            letter-spacing: 3px;
            margin-top: 3px;
        }

        /* MAIN LAYOUT (FIXED GRID STYLE TABLE) */
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .main-table td {
            vertical-align: top;
            padding: 10px;
        }

        .photo-box {
            width: 120px;
            height: 120px;
            border: 1px solid #999;
            background: #f4f4f4;
            text-align: center;
            line-height: 120px;
            color: #666;
        }

        .label {
            width: 140px;
            color: #333;
        }

        .value {
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
        }

        /* RIGHT COLUMN */
        .location-box {
            font-size: 12px;
            line-height: 1.4;
        }

        .location-box strong {
            display: block;
            margin: 6px 0;
        }

        /* WARNINGS */
        .warning-banner {
            font-weight: bold;
            text-transform: uppercase;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
            padding: 8px 0;
            margin: 20px 0 10px;
            text-align: center;
        }

        .notes {
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 12px;
        }

        .must-bring {
            color: #b30000;
            font-weight: bold;
        }

        /* SIMPLE INFO TABLE */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
        }

        .page {
            width: 900px;
            margin: 0 auto;
            /* THIS CENTERS EVERYTHING */
        }
    </style>
</head>

<body>

    <div class="page">

        <div class="header-banner">
            MINISTRY OF HEALTH & WELLNESS
            <span>Public Health Electronic Application Center</span>
        </div>

        <div class="sub-header">
            Official Food Handlers Permit Certificate
        </div>

        <div class="barcode-box">
            <div class="barcode-lines">||| | ||||| || ||| ||||</div>
            <div class="barcode-text">{{ $applicant->permit_no }}</div>
        </div>

        <h1>Confirmation</h1>

        <div class="intro-text">
            This confirms the issuance of the Food Handlers Permit for:
        </div>

        <!-- CENTER WRAPPER TABLE -->
        <table class="main-table">
            <tr>

                <!-- PHOTO -->
                <td style="width: 20%; text-align:center; vertical-align: top;">
                    <div class="photo-box">
                        @if ($applicant->photo_upload)
                            <img src="{{ asset('storage/' . $applicant->photo_upload) }}" alt="Applicant Photo">
                        @else
                            Photo Not Available
                        @endif
                    </div>
                </td>

                <!-- DETAILS (FIXED WIDTH) -->
                <td style="width: 45%;">
                    <table class="info-table">
                        <tr>
                            <td class="label">Name:</td>
                            <td class="value">{{ strtoupper($applicant->lastname) }},
                                {{ strtoupper($applicant->firstname) }}</td>
                        </tr>

                        <tr>
                            <td class="label">Date of Birth:</td>
                            <td class="value">{{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('d M Y') }}
                            </td>
                        </tr>

                        <tr>
                            <td class="label">Permit No:</td>
                            <td class="value">{{ $applicant->permit_no }}</td>
                        </tr>

                        <tr>
                            <td class="label">Category:</td>
                            <td class="value">{{ $applicant->permitCategory->name ?? 'N/A' }}</td>
                        </tr>

                        <tr>
                            <td class="label">Issued:</td>
                            <td class="value">
                                {{ optional($applicant->signOffs)->sign_off_date
                                    ? \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y')
                                    : 'Pending' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="label">Expiry:</td>
                            <td class="value">
                                {{ optional($applicant->signOffs)->expiry_date
                                    ? \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y')
                                    : 'Pending' }}
                            </td>
                        </tr>

                        <tr>
                            <td class="label">Ref No:</td>
                            <td class="value" style="color:#b30000;">
                                {{ strtoupper(uniqid()) }}
                            </td>
                        </tr>
                    </table>
                </td>

                <!-- LOCATION -->
                <td style="width: 35%;">
                    <div class="location-box">
                        <div class="section-title">Location Selected</div>

                        <strong>{{ optional($applicant->establishmentClinics)->name ?? 'MOHW Head Office' }}</strong>

                        Please report to this clinic for screening or renewals.

                        <br><br>
                        Version 01.08.08
                    </div>
                </td>

            </tr>
        </table>

        <div class="warning-banner">
            THIS IS NOT A MEDICAL CLEARANCE
        </div>

        <div class="notes">
            Electronically submitting your application is the first step in the process. You may be required to attend a
            clinic appointment or screening.
        </div>

        <div class="notes">
            <span class="must-bring">YOU MUST BRING:</span>
            Valid Government ID at all stages of processing.
        </div>

    </div>

</body>

</html>
