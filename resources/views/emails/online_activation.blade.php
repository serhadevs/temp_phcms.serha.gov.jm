<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Your Mobile App Account</title>
    <style>
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
            color: #ffffff;
            /* Explicitly set to white */
        }

        .card-body {
            padding: 35px 30px;
            color: #333333;
            /* Changed from #ffffff to dark grey/black for visibility */
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
            /* Dark grey for list items */
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
                color: #ffffff;
                /* Removed the override that made it black */
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
            <h1>You are one step away from activating the Food Handlers Mobile App - Powered By IDPro</h1>
        </div>

        <div class="card-body">
            <p>Dear <strong>{{ Str::ucfirst($applicant['firstname']) }}
                    {{ Str::ucfirst($applicant['lastname']) }}</strong>,</p>

            <p>We are pleased to inform you that your Food Handler’s Application has been officially reviewed, approved,
                and signed off by the Medical Officer of Health.</p>

            <p><strong>Your mobile app account has been created.</strong>
                You are now ready to activate your account and set your password so you can securely access your Food
                Handler’s E-Card from the PHCMS Mobile App anytime.</p>

            <div class="alert-info">
                <h3>Activate your mobile app account:</h3>
                <ol>
                    <li>Enter this code to login into the mobile app</li>

                    <div
                        style="margin: 28px 0; padding: 20px; background-color: #F3F3F3; border-radius: 4px; text-align: center;">
                        <p
                            style="margin: 0 0 10px 0; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 13px; color: #222222;">
                            Your temporary activation code:
                        </p>
                       <p style="color: #E50914; font-family: 'Courier New', Courier, monospace; font-size: 32px; font-weight: bold; text-decoration: none; letter-spacing: 6px; display: inline-block;">
                         {{ $activationCode }}
                       </p>
                            
                    </div>

                    <li>Create your password when prompted.</li>
                    <li>Log in to the IDPro Identity Management App to view and download your Food Handler’s E-Card.(Once the Medical
                        Office of Health signs off your application)</li>
                </ol>
            </div>

            <p style="margin-top: 35px;">
                For security reasons, this activation code will expire in <strong>7 days</strong>.
                If the code expires, please contact your Health Department for assistance.
            </p>

            <p>If you have any questions or need technical assistance, please do not hesitate to contact us.</p>

            <p>Thank you for your commitment to maintaining excellent Public Health Standards!</p>

            <p style="margin-bottom: 0;">
                Best regards,<br>
                <strong>The {{ config('app.name') }} Team</strong>
            </p>
        </div>

        <div class="card-footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
            This is an automated message, please do not reply directly to this email. The E-Card Verification process
            is powered by IDPro.
        </div>
    </div>

</body>

</html>
