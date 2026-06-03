   <style>
    /* Footer Styles */
        .footer {
            background-color: #1a1a1a;
            color: #e0e0e0;
            padding: 50px 0 20px;
            margin-top: auto;
            /* Ensures it stays at bottom if content is short */
        }

        .footer h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .footer a {
            color: #a0a0a0;
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer a:hover {
            color: var(--gov-accent);
        }

        .footer-bottom {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #333;
            text-align: center;
            font-size: 0.85rem;
        }
   </style>
   
   <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-4">
                    <h5 class="text-white d-flex align-items-center gap-2">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0"
                            style="width: 50px; height: 50px;">
                            <img src="{{ asset('images/serha_logo.png') }}" class="logo" alt="SERHA"
                                style="height: 35px; width: auto;">
                        </div>

                        <span class="lh-sm">South East Regional Health Authority</span>
                    </h5>

                    <p class="text-white small pe-4 mt-3">Dedicated to providing excellent service and maintaining the
                        highest standards for the citizens of Jamaica.</p>
                </div>

                <div class="col-lg-2 col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="/verify-permit/home">Home</a></li>
                        <li class="mb-2"><a href="#">Food Handlers Permit</a></li>
                        <li class="mb-2"><a href="#">Establishment Licenses</a></li>
                        {{-- <li class="mb-2"><a href="#">Contact Directory</a></li> --}}
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4">
                    <h5>Government Information</h5>
                    <ul class="list-unstyled small">
                        {{-- <li class="mb-2"><a href="#">Office of the Prime Minister</a></li>
                        <li class="mb-2"><a href="#">Jamaica Information Service</a></li> --}}
                        <li class="mb-2"><a href="{{ route('data-protection') }}">Data Protection Policy</a></li>
                        <li class="mb-2"><a href="{{ route('terms') }}">Terms of Use</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-4">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> Kingston, Jamaica</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> (876) 754-1088</li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i> info@serha.gov.jm</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                &copy; 2026 South East Regional Health Authority. All Rights Reserved. The verification process in this
                application is powered by IDPro. A Duromics Product.
            </div>
        </div>
    </footer>