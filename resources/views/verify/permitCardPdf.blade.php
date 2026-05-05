<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            background: #ffffff;
        }

        .card {
            width: 560px;
            /* 👈 narrower */
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 22px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 15px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 17px;
            letter-spacing: .5px;
        }

        .details-table {
            width: 100%;
            margin-top: 8px;
        }

        .details-left {
            width: 68%;
            /* 👈 adjusted */
            font-size: 13px;
            line-height: 1.7;
        }

        .photo-box {
            width: 110px;
            /* 👈 slightly smaller */
            height: 110px;
            border: 1px solid #ccc;
            text-align: center;
            vertical-align: middle;
            background: #f8f9fa;
        }

        .photo {
            width: 110px;
            height: 110px;
            object-fit: cover;
        }

        .label {
            font-weight: bold;
        }

        .regulation {
            margin-top: 18px;
            font-size: 10px;
            text-align: center;
            color: #555;
        }
    </style>
</head>

<body>



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
                        <div class="regulation">
                            Public Health (Food Handling 1998) Regulations 26,27,28,29,30 & 31
                        </div>
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
                                        <img src="{{ public_path('storage/' . $applicant->photo_upload) }}"
                                            class="photo">
                                    @else
                                        No Photo
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td>
                        The application has now been reviewed and approved by the Medical Officer of Health(MOH). In
                        accordance with the Food Safety Act (1998), individuals who handle, prepare, or come into
                        contact with food for public consumption must be medically examined, certified, and officially
                        authorized before engaging in food-handling activities. With the successful completion of the
                        required examination and medical interview, and the formal sign-off granted, this applicant is
                        now legally recognized as certified to handle food and may operate in compliance with national
                        public health regulations.
                    </td>

                </tr>
            </table>

        </div>
    </center>

</body>

</html>
