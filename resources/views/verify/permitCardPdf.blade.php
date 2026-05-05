<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
    body{
        font-family: DejaVu Sans, sans-serif;
        background:#ffffff;
    }

    .card{
        width:650px;
        border:1px solid #ddd;
        border-radius:20px;
        padding:25px;
    }

    .header-table{
        width:100%;
        margin-bottom:20px;
    }

    .header-table td{
        vertical-align:middle;
    }

    .title{
        text-align:center;
        font-weight:bold;
        font-size:18px;
    }

    .details-table{
        width:100%;
        margin-top:10px;
    }

    .details-left{
        width:70%;
        font-size:14px;
        line-height:1.8;
    }

    .photo-box{
        width:120px;
        height:120px;
        border:1px solid #ccc;
        text-align:center;
        vertical-align:middle;
        background:#f8f9fa;
    }

    .photo{
        width:120px;
        height:120px;
        object-fit:cover;
    }

    .label{
        font-weight:bold;
    }

    /* EXPIRED watermark */
    .expired-watermark{
        position:fixed;
        top:40%;
        left:15%;
        font-size:90px;
        color:red;
        opacity:0.15;
        transform:rotate(-30deg);
        font-weight:bold;
    }
</style>
</head>

<body>

@if($isExpired)
<div class="expired-watermark">EXPIRED</div>
@endif

<center>
<div class="card">

    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td width="20%">
                <img src="{{ public_path('images/coatofarms.png') }}" width="60">
            </td>

            <td width="60%" class="title">
                MIN. OF HEALTH AND WELLNESS
            </td>

            <td width="20%" align="right">
                <img src="{{ public_path('images/mohlogo.png') }}" width="90">
            </td>
        </tr>
    </table>

    <!-- BODY -->
    <table class="details-table">
        <tr>

            <td class="details-left">
                <div><span class="label">Category:</span> Basic Foodhandlers</div>
                <div><span class="label">Name:</span>
                    {{ strtoupper($applicant->lastname) }},
                    {{ strtoupper($applicant->firstname) }}
                </div>

                <div><span class="label">Permit#:</span>
                    {{ $applicant->permit_no ?? 'No Permit Number' }}
                </div>

                <div><span class="label">Issued:</span>
                    {{ optional($applicant->signOffs)->sign_off_date
                        ? \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y')
                        : 'Pending' }}
                </div>

                <div><span class="label">Expires:</span>
                    {{ optional($applicant->signOffs)->expiry_date
                        ? \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y')
                        : 'Pending' }}
                </div>
            </td>

            <td align="right">
                <table class="photo-box">
                    <tr>
                        <td>
                            @if ($applicant->photo_upload)
                                <img src="{{ public_path('storage/'.$applicant->photo_upload) }}" class="photo">
                            @else
                                No Photo
                            @endif
                        </td>
                    </tr>
                </table>
            </td>

        </tr>
    </table>

</div>
</center>

</body>
</html>