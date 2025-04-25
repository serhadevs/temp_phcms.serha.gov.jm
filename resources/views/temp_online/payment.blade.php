<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>PHCMS - Online Application</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Tabler CSS CDN -->
  <link href="https://unpkg.com/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="d-flex flex-column">

  <div class="page page-center">
    <div class="container-tight py-4">
        @include('partials.messages.messages')
      <div class="card card-md">
        <div class="card-body">
          <h2 class="card-title text-center mb-4">Checkout for {{ $permit_application->firstname }} {{ $permit_application->lastname }}</h2>

          <!-- Coupon Input -->
          {{-- <form onsubmit="applyCoupon(event)">
            @csrf
            <div class="mb-3">
                <label class="form-label">Have a coupon?</label>
                <div class="input-group">
                    <input type="text" name="coupon_name" id="coupon_name" class="form-control" placeholder="Enter coupon code">
                    <button id = "coupon_button" class="btn btn-outline-primary" type="submit">Apply</button>
                </div>
            </div>
        </form> --}}
        
        <!-- Optional: Discount display -->
        <div id="discountDisplay" class="mt-2 text-success fw-bold"></div>

          <!-- Payment Form -->
          <form method="POST" action="{{ route('permit.online.application.payment.process') }}" id="formPayment">
            @csrf

            <div class="mb-3">
              <label class="form-label">Card Number</label>
              <input type="text" name="card_number" id="card_number" class="form-control" placeholder="1234 5678 9012 3456">
            </div>

            <div class="row">
              <div class="col-md-6 mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="text" name="expiry" id = "expiry" class="form-control" placeholder="MM/YY">
              </div>
              <div class="col-md-6 mb-3">
                <label class="form-label">CVV</label>
                <input type="text" name="cvv" class="form-control" placeholder="123">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Name on Card</label>
              <input type="text" name="name_on_card" class="form-control" placeholder="Full Name">
            </div>

            <input type="text" hidden name = "application_id" value="{{ $permit_application->id }}">
            <input type="text" hidden name = "price_id" value="1">
            <input type="text" hidden name="amount_paid" value="0.00">
            <input type="text" hidden name="total_cost" value="0.00">
            <input type="text" hidden name="change_amt" value="0.00">
            <input type="text" hidden name="facility_id" value="3">
            <input type="text" hidden name="cashier_user_id" value="124">
            <input type="text" hidden name="payment_type_id" value="1">
            <input type="text" hidden name="application_type_id" value="1">


            <!-- Order Summary -->
            <div class="card mb-3">
              <div class="card-header">
                <h3 class="card-title">Order Summary</h3>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                  <span>Subtotal:</span>
                  <strong>$500.00</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span>Discount:</span>
                  <strong id="discount">-</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                  <span>You Pay:</span>
                  <strong id="total">-</strong>
                </div>
              </div>
            </div>

            <div class="form-footer">
              <button type="submit" class="btn btn-primary w-100">Pay Now</button>
            </div>
          </form>
        </div>
      </div>

      {{-- <div class="text-center text-muted mt-3">
        <a href="/" tabindex="-1">← Back to Shop</a>
      </div> --}}

    </div>
  </div>

  <!-- Tabler JS (optional for interactions) -->
  <script src="https://unpkg.com/@tabler/core@latest/dist/js/tabler.min.js"></script>
  <script>
    const COUPON_REDEEM_URL = "{{ route('coupons.redeem') }}";
</script>
  <script src="{{ asset('js/applyCoupon.js') }}"></script>
</body>
</html>
