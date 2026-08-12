
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onsite(Establishment Clinic) Application Confirmation</title>
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
            <h1>Thank you for Applying for your Food Handlers' Establishment Clinic(Onsite) Application</h1>
        </div>

        <div class="card-body">
            

            <p>We are pleased to inform you that your Food Handlers' Establishment Clinic(Onsite) Application has been received. </p>

            <p>Below is your application information</p>

                <table style="width: 100%; border-collapse: collapse; font-family: sans-serif;">
    <thead>
        <tr>
            <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">Field Name</th>
            <th style="border-bottom: 2px solid #ddd; padding: 8px; text-align: left;">Value / Details</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>Name of Establishment:</strong></td>
            <td>{{ $application->name ?? 'No Name Provided' }}</td>
        </tr>
        <tr>
            <td><strong>Address:</strong></td>
            <td>{{ $application->address ?? 'No Address Provided' }}</td>
        </tr>
        <tr>
            <td><strong>Contact Number:</strong></td>
            <td>{{ $application->telephone ?? 'No Telephone Provided' }}</td>
        </tr>
        <tr>
            <td><strong>Email Address:</strong></td>
            <td>{{ $application->email_address ?? 'No Email Address Provided' }}</td>
        </tr>
        <tr>
            <td><strong>Contact Person:</strong></td>
            <td>{{ $application->contact_person ?? 'No Contact Person Information Provided' }}</td>
        </tr>
        <tr>
            <td><strong>Number of Employees:</strong></td>
            <td>{{ number_format($application->no_of_employees, 0) ?? '0' }}</td> <!-- Added formatting for cleaner display -->
        </tr>
        <tr>
            <td><strong>Date of Application:</strong></td>
            <td>{{  \Carbon\Carbon::parse($application->application_date)->format('d M Y') ?? 'N/A' }}</td> 
            <!-- Note: Ensure Carbon is imported or use now()->diffForHumans() if needed -->
             
        </tr>
        
         <tr>
            <td><strong>Proposed Date of Training:</strong></td>
            <td>{{ \Carbon\Carbon::parse($application->proposed_date)?->format('d F Y') }}</td> <!-- Optional chaining for safety -->

         </tr>
        <tr>
            <td><strong>Proposed Time for Training:</strong></td>
            <td>{{ $application->proposed_time ?? 'N/A' }}</td>
        </tr>
    </tbody>
</table>

            <p style="margin-top: 35px;">If you have any questions regarding your application, please do not hesitate to contact us.</p>

            <p>Thank you for your commitment to maintaining excellent Public Health Standards!</p>

            <p style="margin-bottom: 0;">
                Best regards,<br>
                <strong>The South East Regional Health Authority Team</strong>
            </p>
        </div>

        <div class="card-footer">
            &copy; {{ date('Y') }} South East Regional Health Authority. All rights reserved.<br>
            This is an automated message, please do not reply directly to this email. 
        </div>
    </div>

</body>

</html>