<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation Email</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .footer {
            text-align: left;
            margin-top: 20px;
            color: black;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dear {{ $sendEmailInfo->firstname }} {{ $sendEmailInfo->lastname }},</h1>
        </div>
        
        <p>Thank you for submitting your application for your <strong>{{ $sendEmailInfo->permitCategory->name }}</strong>. We are pleased to inform you that we have received it successfully.</p>
        <br/>
        
        <h2>Below is the appointment information:</h2>
        <br/>
        
        <table class="table table-bordered text-center">
            <thead class="thead-light">
                <tr>
                    <th>Applicant Name</th>
                    <th>Appointment Date</th>
                    <th>Exam Start Time</th>
                    <th>Exam Location</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$sendEmailInfo->firstname}}  {{ $sendEmailInfo->lastname }}</td>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d F Y') }}</td>
                    <td>{{ $appointment->exam_start_time }}</td>
                    <td>{{ $appointment->name }}</td>
                </tr>
            </tbody>
        </table>

        <p><strong>LATE ARRIVALS WILL NOT BE GIVEN A TEST</strong></p>

        <p>If you miss this date, then you must return, <strong>with your receipt</strong>, for a new date. All applicants are required to arrive on time to participate in lecture followed by a written test.</p>

        <h3>Dress Code</h3>
        <p><strong>Female:</strong> Fingernails must be short, clean, and without nail polish. Hair should be properly groomed. Clothing must <strong>NOT</strong> be short or tight. Sleeveless blouses or shirts are not permitted.</p>
        <p><strong>Male:</strong> Fingernails must be short, clean, and hair properly groomed. <strong>NO</strong> sleeves shirts or merinos and cut-off pants.</p>
        <p><strong>Bring a Pen to mark your Test Paper</strong></p>

        <p>If you have any questions or need further assistance, please feel free to contact us at 876-984-3318.</p>

        <p>Thank you again for choosing us. We look forward to assisting you further.</p>
        <p>Thank you,<br>South East Regional Health Authority</p>
    </div>
</body>
</html>
