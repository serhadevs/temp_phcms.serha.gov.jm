<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Secure Link Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f7fb;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-403 {
            max-width: 520px;
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, .08);
            padding: 40px;
            text-align: center;
            background: white;
        }

        .logo-circle {
            width: 90px;
            height: 90px;
            background: white;
            border-radius: 50%;
            box-shadow: 0 4px 18px rgba(0, 0, 0, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: auto;
            margin-bottom: 20px;
        }

        .logo-circle img {
            height: 50px;
        }

        /* .btn-primary{
            background:
            border:none;
            border-radius:10px;
            padding:12px;
            font-weight:600;
        } */

        .btn-outline-secondary {
            border-radius: 10px;
            padding: 12px;
        }
    </style>
</head>

<body>

    <div class="card-403">

        <div class="logo-circle">
            <img src="{{ asset('images/serha_logo.png') }}">
        </div>

        <h3 class="fw-bold text-dark">Secure Link Expired</h3>

        <p class="text-muted mt-3">
            For your security, certificate links expire after a short time.
            This prevents unauthorized access to personal records.
        </p>

        <div class="alert alert-light border mt-3 small">
            Please return to the retrieval page and generate a new secure link.
        </div>

        <hr class="my-4">

        <div class="small text-muted">
            Food Handlers E-Card Permit is verified using <strong>IDPro</strong> — a <strong>Duromics.ca</strong>
            software.
        </div>

        <div class="small text-muted mt-2">
            Error Code: 419 • Session Timeout
        </div>

    </div>

</body>

</html>
