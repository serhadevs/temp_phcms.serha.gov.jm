<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>PHCMS - Online Application - Verify</title>
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

        h1 {
            font-size: 2.5rem !important;
            line-height: 40px;
        }

        .collage {
            display: grid;
            gap: 0.5rem;
            grid-template-rows: repeat(5, 1fr);
            grid-template-columns: repeat(8, 1fr);
            grid-template-areas:
                "..   two two    two    three  four ..    .."
                "one  two two    two    five   five six   .."
                "..   two two    two    five   five seven eight"
                "nine ten eleven eleven twelve ..   ..    .."
                "..   ..  eleven eleven ..     ..   ..    ..";

        }

        .collage img {
            width: 100%;
            aspect-ratio: 1;
            display: block;
            object-fit: cover;
        }

        .collage>div {
            overflow: hidden;
            border-radius: 0.5rem;
        }

        .collage :nth-child(1) {
            grid-area: one;
        }

        .collage :nth-child(2) {
            grid-area: two;
        }

        .collage :nth-child(3) {
            grid-area: three;
        }

        .collage :nth-child(4) {
            grid-area: four;
            background-color: #ffecc3;
            border-bottom-right-radius: 100%;
        }

        .collage :nth-child(5) {
            grid-area: five;
        }

        .collage :nth-child(6) {
            grid-area: six;
            background-color: #ccf0ee;
            border-top-right-radius: 100%;
        }

        .collage :nth-child(7) {
            grid-area: seven;
        }

        .collage :nth-child(8) {
            grid-area: eight;
            background-color: #cdd7f0;
            border-radius: 100%;
        }

        .collage :nth-child(9) {
            grid-area: nine;
            background-color: #ffdce0;
            border-top-left-radius: 100%;
        }

        .collage :nth-child(10) {
            grid-area: ten;
        }

        .collage :nth-child(11) {
            grid-area: eleven;
        }

        .collage :nth-child(12) {
            grid-area: twelve;
        }
    </style>

</head>

<body class=" d-flex flex-column">
    <script src="./tabler/dist/js/demo-theme.min.js?1692870487"></script>
    <div class="page">
        <header class="navbar navbar-expand-md navbar-light d-print-none sticky-top">
            <div class="container-xl">

                <!-- Left Side: Logo -->
                <a href="#" class="navbar-brand d-flex align-items-center">
                    <img src="{{ asset('images/serha_logo.png') }}" width="36" height="36" class="me-2"
                        alt="Logo">
                    <span class="navbar-brand-text fw-bold">SERHA</span>
                </a>

                <!-- Hamburger Menu (Mobile) -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Main Navigation Links -->
                <div class="collapse navbar-collapse" id="navbar-menu">
                    <nav class="navbar-nav flex-grow-1">
                        <div class="d-flex flex-column flex-md-row align-items-md-center">
                            <a href="" class="nav-link px-3">Home</a>
                            <a href="" class="nav-link px-3">Food Handlers Permit</a>
                            <a href="" class="nav-link px-3">Establishment License</a>
                            <a href="" class="nav-link px-3">Swimming Pool License</a>
                        </div>
                    </nav>

                    <!-- Right Side: Login Button -->
                    <div class="d-flex align-items-center mt-3 mt-md-0">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    </div>
                </div>

            </div>
        </header>

        <section class="py-5 bg-white">
            <div class="container-xl">
                <div class="row align-items-center">

                    <!-- Left Side: Text Content -->
                    <div class="col-md-6">
                        <h1 class="fw-bold mb-3">Apply for your <br><span class="text-primary">Food Handlers Permit
                                Online</span>!</h1>
                        <p class="text-muted mb-4 fs-2">
                            Why wait in line? Apply online now and start your journey to certification without ever
                            stepping foot in the health department!
                        </p>

                        <a href="#get-started" class="btn btn-primary">
                            Apply Now!
                        </a>
                    </div>

                    <!-- Right Side: Illustration -->
                    <div class="col-md-6 text-center mt-4">
                        <div class="collage">
                            <div aria-hidden="true">
                                <img src="{{ asset('images/food_8.jpg') }}" alt="Training Illustration">
                            </div>
                            <div>
                                <img src="{{ asset('images/food_5.jpg') }}" alt="">
                            </div>
                            <div>
                                <img src="{{ asset('images/food_3.jpg') }}" alt="">
                            </div>
                            <div>
                                {{-- <img src="{{ asset('images/food_4.jpg') }}" alt="Training Illustration"> --}}
                            </div>
                            <div>
                                <img src="{{ asset('images/food_6.jpg') }}" alt="">
                            </div>
                            <div>
                               
                            </div>
                            <div>
                                <img src="{{ asset('images/food_7.jpg') }}" alt="Training Illustration">
                            </div>
                            <div>
                                
                            </div>
                            <div>
                                
                            </div>
                            <div>
                                <img src="{{ asset('images/food_9.jpg') }}" alt="">
                            </div>
                            <div>
                                <img src="{{ asset('images/food_2.jpg') }}" alt="">
                            </div>
                            <div aria-hidden="true">
                                <img src="{{ asset('images/food_1.jpg') }}" alt="Training Illustration">
                            </div>

                        </div>



                    </div>

                </div>
            </div>
        </section>

        <section class="section py-5">
            <div class="container">
              <div class="row items-center text-center g-lg-10">
                <div class="col-md-6 col-lg">
                  <div class="shape shape-md mb-3">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/devices -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                      <path d="M13 9a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-6a1 1 0 0 1 -1 -1v-10z"></path>
                      <path d="M18 8v-3a1 1 0 0 0 -1 -1h-13a1 1 0 0 0 -1 1v12a1 1 0 0 0 1 1h9"></path>
                      <path d="M16 9h2"></path>
                    </svg>
                  </div>
                  <h2 class="h2">Mobile-optimized</h2>
                  <p class="text-secondary">Our email templates are fully responsive, so you can be sure they will look great on any device and screen size.</p>
                </div>
                <div class="col-md-6 col-lg">
                  <div class="shape shape-md mb-3">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/mailbox -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                      <path d="M10 21v-6.5a3.5 3.5 0 0 0 -7 0v6.5h18v-6a4 4 0 0 0 -4 -4h-10.5"></path>
                      <path d="M12 11v-8h4l2 2l-2 2h-4"></path>
                      <path d="M6 15h1"></path>
                    </svg>
                  </div>
                  <h2 class="h2">Compatible with 90+ email clients</h2>
                  <p class="text-secondary">
                    Tested across 90+ email clients and devices, Tabler emails will help you make your email communication professional and reliable.
                  </p>
                </div>
                <div class="col-md-6 col-lg">
                  <div class="shape shape-md mb-3">
                    <!-- Download SVG icon from http://tabler.io/icons/icon/palette -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                      <path d="M12 21a9 9 0 0 1 0 -18c4.97 0 9 3.582 9 8c0 1.06 -.474 2.078 -1.318 2.828c-.844 .75 -1.989 1.172 -3.182 1.172h-2.5a2 2 0 0 0 -1 3.75a1.3 1.3 0 0 1 -1 2.25"></path>
                      <path d="M8.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                      <path d="M12.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                      <path d="M16.5 10.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                    </svg>
                  </div>
                  <h2 class="h2">Unique, minimal design</h2>
                  <p class="text-secondary">Draw recipients’ attention with beautiful, minimal email designs based on Bootstrap and Material Design principles.</p>
                </div>
              </div>
            </div>
          </section>



        <div class="container container-narrow py-4">
            {{-- <div class="text-center mb-4">
                <a href="." class="navbar-brand navbar-brand-autodark">
                    <img src="./images/serha_logo.png" class="w-7">
                </a>
            </div> --}}
            <form action="{{ route('permit.online.application.verify') }}" method="POST">
                @method('POST')
                @csrf
                <div class="card card-md">
                    <div class="card-body text-center" id="card-1" style="">
                        <h2 class="h2 text-center mb-4">Online Food Handlers Permit Application</h2>
                        <img src="./images/verify.svg" alt="" style="height:40dvh" class="">
                        <div class="mt-3 text-start">
                            <label class="form-label" id="fname_label">Enter email for verification</label>
                            <input type="text" class="form-control" placeholder="john.brown@gmail.com" name="email"
                                autocomplete="off" value="{{ old('email') }}">
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
