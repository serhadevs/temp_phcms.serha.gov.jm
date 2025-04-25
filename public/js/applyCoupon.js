
function applyCoupon(event) {
    event.preventDefault(); // prevent form submission

    const couponInput = document.getElementById('coupon_name');
    const couponBtn = document.getElementById('coupon_button')
    const formInput = document.getElementById('formPayment').querySelectorAll('input, select, textarea');
    const discount = document.getElementById('discount');
    const total = document.getElementById('total')
    let couponName = couponInput.value;

    if (!couponName) {
        alert("Please enter a coupon code.");
        return;
    }


    fetch(COUPON_REDEEM_URL, {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ coupon_name: couponName })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            console.log(data)
            // Optional: update discount and total
            document.getElementById('discountDisplay').innerText = `Discount: $${data.coupon.coupon_discount}`;
            //Disable the inputs 
            couponInput.disabled = true;
            couponBtn.disabled = true;
            discount.innerText = `$${data.coupon.coupon_discount}`
            total.innerText = 0.00
            Array.from(formInput).forEach(element => {
                element.disabled = true;
            });

        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error("Error:", error);
        alert("Something went wrong while applying the coupon.");
    });
}

