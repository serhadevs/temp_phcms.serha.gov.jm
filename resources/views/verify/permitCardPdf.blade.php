<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary-color: #004a99; /* Ministry Blue */
            --text-main: #333333;
            --text-muted: #666666;
        }

        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .card {
            background: #ffffff;
            width: 100%;
            max-width: 600px; /* Allows it to shrink on mobile */
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            box-sizing: border-box;
        }

        /* --- HEADER --- */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header img {
            max-height: 65px;
            width: auto;
            flex-shrink: 0;
        }

        .header-title {
            text-align: center;
            flex-grow: 1;
            padding: 0 15px;
        }

        .header-title h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
            color: var(--primary-color);
            letter-spacing: 0.5px;
        }

        .header-title .regulation {
            font-size: 10px;
            color: var(--text-muted);
            line-height: 1.3;
        }

        /* --- CONTENT SECTION --- */
        .content {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .details {
            flex-grow: 1;
            font-size: 14px;
            line-height: 1.6;
        }

        .data-row {
            display: flex;
            padding: 6px 0;
            border-bottom: 1px dashed #eeeeee;
        }

        .data-row:last-child {
            border-bottom: none;
        }

        .data-label {
            font-weight: bold;
            color: var(--primary-color);
            width: 90px;
            flex-shrink: 0;
        }

        .data-value {
            font-weight: 600;
            color: var(--text-main);
        }

        /* --- PHOTO SECTION --- */
        .photo-wrapper {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .photo-box {
            width: 120px;
            height: 120px;
            border: 2px solid #cccccc;
            border-radius: 12px;
            overflow: hidden;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- RESULTS/APPROVAL SECTION --- */
        .results-box {
            background-color: #f0f7ff;
            border-left: 4px solid var(--primary-color);
            padding: 15px;
            border-radius: 4px;
            font-size: 12px;
            line-height: 1.6;
            color: #444;
            text-align: justify;
        }

        .badge-verified {
            display: inline-block;
            background-color: #28a745;
            color: white;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 10px;
            border-radius: 20px;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }

        /* --- MOBILE RESPONSIVENESS --- */
        @media (max-width: 500px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }
            .content {
                flex-direction: column-reverse; /* Puts photo on top on mobile */
                align-items: center;
                text-align: center;
            }
            .data-row {
                justify-content: center;
                flex-direction: column;
                border-bottom: none;
                margin-bottom: 8px;
            }
            .data-label {
                width: 100%;
                margin-bottom: 2px;
            }
            .results-box {
                text-align: left;
            }
        }
    </style>
</head>
<body>

    <div class="card">
        
        <div class="header">
            <img src="{{ public_path('images/coatofarms.png') }}" alt="Coat of Arms">
            
            <div class="header-title">
                <h2>MIN. OF HEALTH AND WELLNESS</h2>
                <div class="regulation">
                    Public Health (Food Handling 1998)<br>Regulations 26, 27, 28, 29, 30 & 31
                </div>
            </div>

            <img src="{{ public_path('images/mohlogo.png') }}" alt="MOH Logo">
        </div>

        <div class="content">
            <div class="details">
                <div class="data-row">
                    <span class="data-label">Category:</span>
                    <span class="data-value">{{ $applicant->permitCategory->name ?? 'Basic Foodhandlers' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Name:</span>
                    <span class="data-value">{{ strtoupper($applicant->lastname) }}, {{ strtoupper($applicant->firstname) }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Permit #:</span>
                    <span class="data-value">{{ $applicant->permit_no ?? 'Pending Assignment' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Issued:</span>
                    <span class="data-value">
                        {{ optional($applicant->signOffs)->sign_off_date ? \Carbon\Carbon::parse($applicant->signOffs->sign_off_date)->format('d M Y') : 'Pending' }}
                    </span>
                </div>
                <div class="data-row">
                    <span class="data-label">Expires:</span>
                    <span class="data-value" style="color: #d9534f;">
                        {{ optional($applicant->signOffs)->expiry_date ? \Carbon\Carbon::parse($applicant->signOffs->expiry_date)->format('d M Y') : 'Pending' }}
                    </span>
                </div>
            </div>

            <div class="photo-wrapper">
                <div class="photo-box">
                    @if ($applicant->photo_upload)
                        <img src="{{ public_path('storage/' . $applicant->photo_upload) }}" alt="Applicant Photo">
                    @else
                        No Photo
                    @endif
                </div>
            </div>
        </div>

        <div class="results-box">
            <span class="badge-verified">✓ OFFICIALLY VERIFIED</span><br>
            The application has now been reviewed and approved by the Medical Officer of Health (MOH). In
            accordance with the Food Safety Act (1998), individuals who handle, prepare, or come into
            contact with food for public consumption must be medically examined, certified, and officially
            authorized before engaging in food-handling activities. With the successful completion of the
            required examination and medical interview, and the formal sign-off granted, this applicant is
            now legally recognized as certified to handle food and may operate in compliance with national
            public health regulations.
        </div>

    </div>

</body>
</html>