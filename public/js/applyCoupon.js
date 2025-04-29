function applyCoupon(event) {
    event.preventDefault(); // prevent form submission

    const couponInput = document.getElementById("coupon_name");
    const total = document.getElementById("total_cost");
    const amount_paid = document.getElementById("amount_paid");
    const couponPaymentForm = document.getElementById('couponPaymentForm')
    let couponName = couponInput.value;

    if (!couponName) {
        Swal.fire({
            icon: "error",
            title: "No Coupon!",
            text: "It looks like you did not enter a valid Coupon!",
            confirmButtonText: "OK",
            confirmButtonColor: "#206bc4",
        });

        return;
    }

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
