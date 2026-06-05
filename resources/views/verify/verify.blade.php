<!DOCTYPE html>
<html>
<head>
    <title>Permit Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
        }
        body{
            font-family: Arial, Helvetica, sans-serif;
            background:#f4f6f9;
            margin:0;
            padding:0;
        }

        .card{
            max-width:600px;
            width: calc(100% - 32px);
            margin:40px auto;
            background:white;
            padding:30px;
            border-radius:16px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
        }

        .header{
            text-align:center;
            margin-bottom:25px;
        }

        .status-valid{
            color:#16a34a;
            font-weight:bold;
            font-size:22px;
        }

        .status-invalid{
            color:#dc2626;
            font-weight:bold;
            font-size:22px;
        }

         .row {
            margin: 12px 0;
            font-size: 18px;
            word-break: break-word;  
        }

        .label{
            font-weight:bold;
        }

        .footer{
            text-align:center;
            margin-top:25px;
            color:#888;
            font-size:14px;
        }
    </style>
</head>
<body>

<div class="card">

    <div class="header">
        <h2>Ministry of Health & Wellness</h2>
        <h3>Food Handler Permit Verification</h3>
    </div>

    @if(!$found)

        <p class="status-invalid">❌ Permit Not Found</p>
        <p>This permit number does not exist in the system.</p>

    @else

        <p class="status-valid">✔ Valid Permit</p>

        <div class="row">
            <span class="label">Permit Number:</span>
            {{ $applicant->permit_no }}
        </div>

        <div class="row">
            <span class="label">Name:</span>
            {{ $applicant->firstname }} {{ $applicant->lastname }}
        </div>

        <div class="row">
            <span class="label">Category:</span>
            {{ $applicant->permitCategory->name ?? 'N/A' }}
        </div>

        <div class="row">
            <span class="label">Status:</span>
            {{ $applicant->sign_off_status ?? 'Pending' }}
        </div>

        <div class="row">
            <span class="label">Issued Date:</span>
            {{ optional($applicant->signOffs)->sign_off_date ?? 'Pending' }}
        </div>

        <div class="row">
            <span class="label">Expiry Date:</span>
            {{ optional($applicant->signOffs)->expiry_date ?? 'Pending' }}
        </div>

        <div class="row">
            <span class="label">Clinic:</span>
            {{ optional($applicant->establishmentClinics)->name ?? 'N/A' }}
        </div>

    @endif

    <div class="footer">
        Official Digital Verification System
    </div>

</div>

</body>
</html>