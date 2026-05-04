<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session Expired</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body{
            background:#f4f7fb;
            min-height:100vh;
            display:flex;
            align-items:center;
            justify-content:center;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-419{
            max-width:520px;
            border:none;
            border-radius:18px;
            box-shadow:0 10px 40px rgba(0,0,0,.08);
            padding:40px;
            text-align:center;
            background:white;
        }

        .logo-circle{
            width:90px;
            height:90px;
            background:white;
            border-radius:50%;
            box-shadow:0 4px 18px rgba(0,0,0,.1);
            display:flex;
            align-items:center;
            justify-content:center;
            margin:auto;
            margin-bottom:20px;
        }

        .logo-circle img{ height:50px; }

        .btn-primary{
            background:linear-gradient(to right,#003366,#b30000);
            border:none;
            border-radius:10px;
            padding:12px;
            font-weight:600;
        }

        .btn-outline-secondary{
            border-radius:10px;
            padding:12px;
        }
    </style>
</head>

<body>

<div class="card-419">

    <div class="logo-circle">
        <img src="{{ asset('images/serha_logo.png') }}">
    </div>

    <h3 class="fw-bold text-dark">Session Expired</h3>

    <p class="text-muted mt-3">
        Your session timed out due to inactivity.
        This helps keep your personal information secure.
    </p>

    <div class="alert alert-light border mt-3 small">
        Please reload the page and try again.
    </div>

    <div class="d-grid gap-2 mt-4">
        <button onclick="location.reload()" class="btn btn-primary">
             Reload Page
        </button>

        <a href="{{ url('/verify-permit') }}" class="btn btn-outline-secondary">
            Go to Retrieval Page
        </a>
    </div>

    <div class="small text-muted mt-4">
        Error Code: 419 • Session Timeout
    </div>

</div>

</body>
</html>