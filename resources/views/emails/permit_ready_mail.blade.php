{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Permit is Ready</title>
    <style>
        /* Bootstrap-inspired fallback styles */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 40px 20px;
        }

        .card {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }

        .card-header {
            background-color: #0b4ea2;
         
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }

        .card-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card-body {
            padding: 35px 30px;
            color: #ffffff;
            line-height: 1.6;
            font-size: 15px;
        }

        .alert-info {
            background-color: #e9f1fb;
            border-left: 4px solid #0b4ea2;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }

        .alert-info h3 {
            margin-top: 0;
            color: #0b4ea2;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .alert-info ol {
            margin: 0;
            padding-left: 20px;
            color: #444;
        }

        .alert-info li {
            margin-bottom: 8px;
        }

        .btn-success {
            display: inline-block;
            padding: 14px 28px;
            background-color: #198754;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin-top: 10px;
        }

        .text-center {
            text-align: center;
        }

        .card-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px 15px;
            }

            .card {
                max-width: 100%;
            }

            .card-header {
                padding: 20px 15px;
            }

            .card-header h1 {
                font-size: 18px;
                color: black;
            }

            .card-body {
                padding: 25px 20px;
                font-size: 14px;
            }

            .alert-info {
                margin: 20px 0;
                padding: 15px;
            }

            .alert-info h3 {
                font-size: 15px;
            }

            .alert-info ol {
                padding-left: 18px;
            }

            .btn-success {
                display: block;
                width: 100%;
                padding: 16px 20px;
                font-size: 15px;
                margin-top: 15px;
                box-sizing: border-box;
            }

            .card-footer {
                padding: 15px;
                font-size: 11px;
            }

            a {
                word-break: break-word;
            }
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="card-header">
            <h1>Your Food Handlers E-Card Permit is Ready!</h1>
        </div>

        <div class="card-body">
            <p>Dear <strong>{{ Str::ucfirst($application['firstname']) }}
                    {{ Str::ucfirst($application['lastname']) }}</strong>,</p>

            <p>We are pleased to inform you that your Food Handler's Application has been officially reviewed, approved,
                and signed off by the Medical Officer of Health.</p>

            <p>We have great news: <strong>Your official E-Card is ready to use right now!</strong> You can use this
                digital certificate immediately as legal proof of your authorization to handle food.</p>

            <div class="alert-info">
                <h3>How to get your E-Card:</h3>
                <ol>
                    <li>Click on this link: <br>
                        <a href="https://phcms.serha.gov.jm/verify-permit/verify/{{ $application->permit_no }}"
                            style="color: #0b4ea2; text-decoration: underline;">
                            https://phcms.serha.gov.jm/verify-permit/verify/{{ $application->permit_no }}
                        </a>
                    </li>
                    <li>Click on <strong>"Download E-Card"</strong> to view your Food Handlers E-Card Permit.</li>
                    <li>Save it to your mobile device or print a copy for your employer.</li>
                </ol>
            </div>


            <p style="margin-top: 35px;">If you have any questions regarding your application or need technical
                assistance, please do not hesitate to contact us.</p>

            <p>Thank you for your commitment to maintaining excellent Public Health Standards!</p>

            <p style="margin-bottom: 0;">
                Best regards,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>

        <div class="card-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            This is an automated message, please do not reply directly to this email. The E-Card Verification process
            was done using IDPro.
        </div>
    </div>

</body>

</html> --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Permit is Ready</title>
    <style>
        /* Bootstrap-inspired fallback styles */
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 40px 20px;
        }

        .card {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
        }

        .card-header {
            background-color: #0b4ea2;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }

        .card-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: #ffffff; /* Explicitly set to white */
        }

        .card-body {
            padding: 35px 30px;
            color: #333333; /* Changed from #ffffff to dark grey/black for visibility */
            line-height: 1.6;
            font-size: 15px;
        }

        .alert-info {
            background-color: #e9f1fb;
            border-left: 4px solid #0b4ea2;
            padding: 20px;
            border-radius: 6px;
            margin: 25px 0;
        }

        .alert-info h3 {
            margin-top: 0;
            color: #0b4ea2;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .alert-info ol {
            margin: 0;
            padding-left: 20px;
            color: #444; /* Dark grey for list items */
        }

        .alert-info li {
            margin-bottom: 8px;
        }

        .btn-success {
            display: inline-block;
            padding: 14px 28px;
            background-color: #198754;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin-top: 10px;
        }

        .text-center {
            text-align: center;
        }

        .card-footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #6c757d;
            background-color: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px 15px;
            }

            .card {
                max-width: 100%;
            }

            .card-header {
                padding: 20px 15px;
            }

            .card-header h1 {
                font-size: 18px;
                color: #ffffff; /* Removed the override that made it black */
            }

            .card-body {
                padding: 25px 20px;
                font-size: 14px;
            }

            .alert-info {
                margin: 20px 0;
                padding: 15px;
            }

            .alert-info h3 {
                font-size: 15px;
            }

            .alert-info ol {
                padding-left: 18px;
            }

            .btn-success {
                display: block;
                width: 100%;
                padding: 16px 20px;
                font-size: 15px;
                margin-top: 15px;
                box-sizing: border-box;
            }

            .card-footer {
                padding: 15px;
                font-size: 11px;
            }

            a {
                word-break: break-word;
            }
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="card-header">
            <h1>Your Food Handlers E-Card Permit is Ready!</h1>
        </div>

        <div class="card-body">
            <p>Dear <strong>{{ Str::ucfirst($application['firstname']) }}
                    {{ Str::ucfirst($application['lastname']) }}</strong>,</p>

            <p>We are pleased to inform you that your Food Handler's Application has been officially reviewed, approved,
                and signed off by the Medical Officer of Health.</p>

            <p>We have great news: <strong>Your official E-Card is ready to use right now!</strong> You can use this
                digital certificate immediately as legal proof of your authorization to handle food.</p>

            <div class="alert-info">
                <h3>How to get your E-Card:</h3>
                <ol>
                    <li>Click on this link: <br>
                        <a href="https://phcms.serha.gov.jm/api/verify-permit/{{ $application->permit_no }}"
                            style="color: #0b4ea2; text-decoration: underline;">
                            https://phcms.serha.gov.jm/api/verify-permit/{{ $application->permit_no }}
                        </a>
                    </li>
                    <li>Click on <strong>"Download E-Card"</strong> to view your Food Handlers E-Card Permit.</li>
                    <li>Save it to your mobile device or print a copy for your employer.</li>
                </ol>
            </div>


            <p style="margin-top: 35px;">If you have any questions regarding your application or need technical
                assistance, please do not hesitate to contact us.</p>

            <p>Thank you for your commitment to maintaining excellent Public Health Standards!</p>

            <p style="margin-bottom: 0;">
                Best regards,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>

        <div class="card-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            This is an automated message, please do not reply directly to this email. The E-Card Verification process
            was done using IDPro.
        </div>
    </div>

</body>

</html>