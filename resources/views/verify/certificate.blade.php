<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
    body{
        font-family: DejaVu Sans, sans-serif;
        background:#f2f2f2;
    }

    .page{
        width:100%;
        text-align:center;
        margin-top:120px;
    }

    .card{
        width:520px;
        height:300px;
        margin:auto;
        border-radius:16px;
        border:2px solid #0b5ed7;
        background:#ffffff;
        padding:18px;
    }

    .header{
        text-align:center;
        font-size:13px;
        line-height:1.4;
        margin-bottom:10px;
    }

    .title{
        font-size:18px;
        font-weight:bold;
        margin-bottom:8px;
        color:#0b5ed7;
    }

    .photo{
        width:120px;
        height:140px;
        border:2px solid #999;
        text-align:center;
        font-size:12px;
    }

    .details{
        font-size:13px;
        text-align:left;
        padding-right:10px;
    }

    .label{
        font-weight:bold;
    }

    .valid{
        text-align:center;
        font-size:13px;
        font-weight:bold;
        margin-top:8px;
        padding-top:6px;
        border-top:1px solid #ccc;
    }

    .footer{
        text-align:center;
        font-size:10px;
        margin-top:6px;
        color:#777;
    }
</style>
</head>

<body>
<div class="page">

    <div class="card">

        <div class="header">
            <strong>SOUTH EAST REGIONAL HEALTH AUTHORITY</strong><br>
            Public Health Certificate Management System
        </div>

        <div class="title">
            FOOD HANDLERS PERMIT
        </div>

        <table width="100%">
            <tr>
                <td class="details" width="70%">
                    <p><span class="label">Name:</span><br>
                        {{ strtoupper($applicant->lastname) }}, {{ strtoupper($applicant->firstname) }}
                    </p>

                    <p><span class="label">Date of Birth:</span><br>
                        {{ \Carbon\Carbon::parse($applicant->date_of_birth)->format('d M Y') }}
                    </p>

                    <p><span class="label">Permit No:</span><br>
                        {{ $applicant->permit_no }}
                    </p>

                    <p><span class="label">Category:</span><br>
                        {{ $applicant->permitCategory->name ?? 'Food Handler' }}
                    </p>
                </td>

                <td width="30%">
                    <div class="photo">
                        PHOTO
                    </div>
                </td>
            </tr>
        </table>

        <div class="valid">
            @if ($applicant->signOffs && now()->lt($applicant->signOffs->expiry_date))
                VALID UNTIL {{ \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y') }}
            @else
                NOT VALID / EXPIRED
            @endif
        </div>

        <div class="footer">
            Verified by IDPro Secure Platform • SERHA Jamaica
        </div>

    </div>

</div>
</body>
</html>