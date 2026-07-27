<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $appName ?? 'Verify Onsite' }} — Sign In</title>

    <style>
        :root {
            /* ---- swap these to reskin the whole page ---- */
            --bg-gradient-start: #060b18;
            --bg-gradient-end: #1c3a63;
            --card-bg: #ffffff;
            --card-radius: 20px;
            --text-primary: #16181d;
            --text-muted: #6b7280;
            --input-border: #c9ccd3;
            --input-border-focus: #3b5bfd;
            --button-bg: #3b5bfd;
            --button-bg-hover: #2f49d6;
            --button-text: #ffffff;
            --link-color: #3b5bfd;
            --footer-text: #cbd5e1;
            --divider-color: #e3e5ea;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: radial-gradient(ellipse at top left, var(--bg-gradient-end) 0%, var(--bg-gradient-start) 65%);
            padding: 40px 20px;
        }

        .card {
            width: 100%;
            max-width: 600px;
            background: var(--card-bg);
            border-radius: var(--card-radius);
            padding: 60px 40px 60px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.35);
            text-align: center;
        }

        .logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 28px;
        }

        .logo-mark {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: var(--button-bg);
            display: inline-block;
        }

        .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
        }

        h1 {
            font-size: 30px;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 28px;
        }

        .field {
            text-align: left;
            margin-bottom: 20px;
        }

        .field input {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            border: 1px solid var(--input-border);
            border-radius: 10px;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .field input:focus {
            border-color: var(--input-border-focus);
        }

        .field .error {
            margin-top: 6px;
            font-size: 13px;
            color: #d92d20;
        }

        button.submit {
            width: 100%;
            padding: 14px 16px;
            font-size: 16px;
            font-weight: 600;
            color: var(--button-text);
            background: var(--button-bg);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        button.submit:hover {
            background: var(--button-bg-hover);
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 28px 0;
            color: var(--text-muted);
            font-size: 13px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--divider-color);
        }

        .secondary-link {
            display: inline-block;
            font-size: 15px;
            font-weight: 600;
            color: var(--link-color);
            text-decoration: none;
        }

        .secondary-link:hover {
            text-decoration: underline;
        }

        .sub-footer {
            margin-top: 28px;
            font-size: 14px;
            color: var(--footer-text);
        }

        .sub-footer a {
            color: var(--footer-text);
            text-decoration: underline;
        }

        .legal-footer {
            margin-top: 60px;
            text-align: center;
            font-size: 13px;
            color: var(--footer-text);
        }

        .legal-footer a {
            color: var(--footer-text);
            text-decoration: underline;
        }

        .legal-footer .sep {
            margin: 0 8px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 14px;
            margin-bottom: 20px;
            text-align: left;
        }
    </style>
</head>

<body>

    <div class="card">
        <div class="logo">
            {{-- <span class="logo-mark"></span> --}}
            <span class="logo-text">Food Handlers Permit Onsite Retreival</span>
        </div>

        <h1>Welcome</h1>
        @if (session('error'))
            <div class="alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul style="margin:0; padding-left: 18px; text-align:left;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('verify.onsite.submit') }}">
            @csrf

            <div class="field">
                <input type="text" name="company_name" placeholder="Enter your company name"
                    value="{{ old('company_name') }}" autofocus>
                @error('company_name')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <input type="text" name="application_number" placeholder="Application Number"
                    value="{{ old('application_number') }}" autofocus>
                @error('application_number')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>


            <div class="field">
                <input type="email" name="email_address" placeholder="Email Address"
                    value="{{ old('email_address') }}" autofocus>
                @error('email_address')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="submit">Retrieve Permits</button>
        </form>


    </div>
    {{-- 
    <div class="sub-footer">
        Login to <a href="#">Service One</a> &nbsp;|&nbsp; Login to <a href="#">Service Two</a>
    </div> --}}

    <div class="legal-footer">
        <a href="#">Terms of Service</a><span class="sep">|</span><a href="#">Privacy Policy</a>
        <div style="margin-top: 8px;">
            &copy; {{ date('Y') }} South East Regional Health Authority . All rights reserved. Verification powered
            By Duromics.ca
        </div>
    </div>

</body>

</html>
