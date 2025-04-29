<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Food Permit Confirmation</title>
  <link href="https://unpkg.com/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet">
</head>
<body class="border-top-wide border-primary d-flex flex-column">

    <header class="navbar navbar-expand-md navbar-light d-print-none sticky-top">
        <div class="container-xl">
          <a href="#" class="navbar-brand d-flex align-items-center">
            <img src="{{ asset('images/serha_logo.png') }}" width="36" height="36" class="me-2" alt="Logo">
            <span class="navbar-brand-text">SERHA</span>
          </a>
          <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item">
              <a href="{{ route('permit.online.confirm') }}" class="btn btn-outline-primary">Home</a>
            </div>
          </div>
        </div>
      </header>

  <div class="page page-center">
    <div class="container container-tight py-4">
      <div class="text-center mb-4">
        <img src="{{ asset('images/serha_logo.png') }}" width="50px" alt="Tabler Logo">
        <p class="text-muted mb-4">South East Regional Health Authority.</p>
      </div>

      <div class="card shadow-lg">
        <div class="card-body text-center py-5">
          <div class="mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg text-primary icon-tabler icon-tabler-circle-check" width="64" height="64" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <circle cx="12" cy="12" r="9" />
              <path d="M9 12l2 2l4 -4" />
            </svg>
          </div>
          <h2 class="card-title text-primary mb-3">Application Submitted Successfully!</h2>
          <p class="text-muted mb-4">Thank you for completing your Food Handlers Permit Application. Your details have been received and are being reviewed.</p>

          <div class="table-responsive mb-4">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <th>Application ID</th>
                  <td>{{ $application->id ?? 'N/A' }}</td>
                </tr>
                <tr>
                  <th>Full Name</th>
                  <td>{{ $application->firstname ?? '' }} {{ $application->lastname ?? '' }}</td>
                </tr>
                {{-- <tr>
                  <th>Business Name</th>
                  <td>{{ $application->business_name ?? 'N/A' }}</td>
                </tr> --}}
                <tr>
                  <th>Date Submitted</th>
                  <td>{{ \Carbon\Carbon::parse($application->created_at)->format('F j, Y') ?? now()->format('F j, Y') }}</td>
                </tr>
                <tr>
                  <th>Status</th>
                  <td><span class="badge bg-blue text-blue-fg">Training and Asssement</span></td>
                </tr>
              </tbody>
            </table>
          </div>

          {{-- <a href="{{ route('home') }}" class="btn btn-primary w-100">Return to Homepage</a> --}}
        </div>
      </div>

      {{-- <div class="text-center text-muted mt-4">
        &copy; {{ date('Y') }} South East Regional Health Authority All rights reserved.
      </div> --}}

      <footer class="footer footer-transparent d-print-none mt-auto py-3 text-center">
        <div class="container">
          <p class="mb-0 text-muted">&copy; {{ date('Y') }} South East Regional Health Authority. All rights reserved.</p>
        </div>
      </footer>
    </div>
  </div>

  <script src="https://unpkg.com/@tabler/core@latest/dist/js/tabler.min.js"></script>
</body>
</html>
