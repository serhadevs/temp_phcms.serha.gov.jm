<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>PHCMS - Online Application</title>
    <base href="/">
    <link href="./tabler/dist/css/tabler.min.css?1692870487" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }

        #signature-pad {
            cursor: pointer;
        }
    </style>

</head>

<body class=" d-flex flex-column">
    <script src="./tabler/dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
        <div class="container container-narrow py-4">
            <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                    <img src="./images/serha_logo.png" class="w-7">
                </a>
            </div>
            <form action="{{ route('permit.online.application.verify') }}" method="POST">
                @method('POST')
                @csrf
                <div class="card card-md">
                    <div class="card-body text-center" id="card-1" style="">
                        <h2 class="h2 text-center mb-4">Online Food Handlers Permit Application</h2>
                        <img src="./images/verify.svg" alt="" style="height:40dvh" class="">
                        <div class="mt-3 text-start">
                            <label class="form-label" id="fname_label">Enter email for verification</label>
                            <input type="text" class="form-control" placeholder="john.brown@gmail.com"
                                name="email" autocomplete="off" value="{{ old('email') }}">
                            @error('email')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary w-100" onclick="">
                                Verify Email</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script src="./tabler/dist/js/tabler.min.js?1692870487" defer></script>
    <script src="./tabler/dist/js/demo.min.js?1692870487" defer></script>
</body>

</html>
