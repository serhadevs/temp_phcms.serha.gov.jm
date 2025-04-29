<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PHCMS - Online Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Tabler CSS CDN -->
    <link href="https://unpkg.com/@tabler/core@latest/dist/css/tabler.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="d-flex flex-column">

    <div class="page page-center">
        <div class="container-tight py-4">
            @include('partials.messages.messages')
            <div class="card card-md">
                <div class="card-body text-center">
                    <h1 class="card-title mb-4" style="font-size: 1.5rem;">
                        Checkout for {{ $permit_application->firstname }} {{ $permit_application->lastname }}
                    </h1>
                
                    <!-- Coupon Input -->
                    <form onsubmit="applyCoupon(event)">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" style="font-size: 1rem;">Have a coupon?</label><br>
                            <div class="d-flex justify-content-center flex-wrap gap-2">
                                <button type="button" class="btn btn-primary" onclick="showCouponInput(true)">Yes I Do</button>
                                <button type="button" class="btn btn-secondary" onclick="showCouponInput(false)">No I Do Not</button>
                            </div>
                
                            <!-- Coupon input group, hidden by default -->
                            <div class="input-group mt-4 justify-content-center" id="couponInputGroup" style="display: none; max-width: 400px; margin: 0 auto;">
                                <input type="text" name="coupon_name" id="coupon_name" class="form-control form-control" placeholder="Enter coupon code">
                                <button id="coupon_button" class="btn btn-outline-primary" type="submit">Apply</button>
                            </div>

                            
                        </div>
                    </form>
                    <form onsubmit="couponPayment(event)" id="couponPaymentForm" style="display: none; max-width: 400px; margin: 0 auto;">
                        <input type="text" hidden name = "application_id" value="{{ $permit_application->id }}">
                        <input type="text" hidden name = "price_id" value="1">
                        <input type="text" hidden name="amount_paid" id = "amount_paid">
                        <input type="text" hidden name="total_cost" id = "total_cost">
                        <input type="text" hidden name="change_amt" value="0.00">
                        <input type="text" hidden name="facility_id" value="3">
                        <input type="text" hidden name="cashier_user_id" value="124">
                        <input type="text" hidden name="payment_type_id" value="1">
                        <input type="text" hidden name="application_type_id" value="1">
                        <button type="submit" class = "btn btn-primary">Make Payment</button>
                    </form>
                
                    <!-- Optional: Discount display -->
                    <div id="discountDisplay" class="mt-3 text-success fw-bold" style="font-size: 1.25rem;"></div>
                </div>
                

        </div>
    </div>

    <!-- Tabler JS (optional for interactions) -->
    <script src="https://unpkg.com/@tabler/core@latest/dist/js/tabler.min.js"></script>
    <script>
        const COUPON_REDEEM_URL = "{{ route('coupons.redeem') }}";
    </script>
    <script>
        function showCouponInput(show) {
        const couponGroup = document.getElementById('couponInputGroup');
       
        if (show) {
            couponGroup.style.display = 'flex';
            
        } else {
            
            couponGroup.style.display = 'none';
            
        }
    }
    </script>

    <script>
        
        function couponPayment(event){
            event.preventDefault();
            fetch(COUPON_REDEEM_URL, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({ coupon_name: couponName }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Valid Coupon",
                    text: data.message ?? "No Message",
                    confirmButtonText: "OK",
                    confirmButtonColor: "#206bc4",
                });

                console.log(data);
                // Optional: update discount and total
                document.getElementById(
                    "discountDisplay"
                ).innerText = `Discount: $${data.coupon.coupon_discount}`;
               
                couponPaymentForm.style.display = 'flex'
                total.value = parseFloat(0).toFixed(2);
                amount_paid.value = parseFloat(0).toFixed(2);

                console.log(total.value, amount_paid.value);
                
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.message,
                    confirmButtonText: "OK",
                    confirmButtonColor: "#206bc4",
                    // timer: 3000,
                    // timerProgressBar: true
                });
                couponPaymentForm.style.display = 'none';
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Something went wrong while applying the coupon.");
        }); 
        }
    </script>
    <script src="{{ asset('js/applyCoupon.js') }}"></script>
</body>

</html>
