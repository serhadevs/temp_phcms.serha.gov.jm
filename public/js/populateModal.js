let app_id;
let establishment_id;
let waiver_application_id;

function populateModal(application, waiver_check) {
    console.log(application);
    
    const modalTitle = document.getElementById('modalTitle');
    modalTitle.textContent = `Request Waiver for ${application.name}`;
    
    app_id = application.id;
    establishment_id = application.id;
    waiver_application_id = waiver_check[0].id;

    console.log("Establishment ID: " + establishment_id);
    console.log("Waiver Application ID: " + waiver_application_id);

    // Optional: prefill the amount field if waiver_check has a previous amount
    if(waiver_check[0].amount) {
        document.querySelector('input[name="waiver_amount"]').value = waiver_check[0].amount;
    }
}


function submitWaiver() {
    const tokenMeta = document.querySelector('meta[name="csrf-token"]');
         const csrfToken = tokenMeta ? tokenMeta.content : '{{ csrf_token() }}'; // fallback
    const amount = document.querySelector('input[name="waiver_amount"]').value;

    fetch({!! json_encode('/waivers/store') !!}, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            waiver_establishment_id: establishment_id,
            application_id: app_id,
            waiver_amount: amount
        })
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success') {
            Swal.fire('Success!', data.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Error!', data.message, 'error');
        }
    })
    .catch(error => {
        console.error(error);
        Swal.fire('Error!', 'Something went wrong.', 'error');
    });
}
