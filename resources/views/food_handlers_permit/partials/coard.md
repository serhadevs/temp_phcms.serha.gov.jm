<div class="modal fade" id="cardModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter Pickup Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="cardpickup">
                    @csrf

                    <!-- Application ID -->
                    <div class="form-group mt-2 d-flex align-items-center">
                        <label for="card_app_id" class="me-2 mb-0" style="width: 120px;">Application Id</label>
                        <input type="text" id="card_app_id" class="form-control" readonly name="app_id">
                    </div>

                    <!-- Occupation -->
                    <div class="form-group mt-2 d-flex align-items-center">
                        <label for="occupation" class="me-2 mb-0" style="width: 120px;">Occupation</label>
                        <input type="text" id="occupation" class="form-control"
                            value="{{ $permit_application->occupation }}" name="occupation" readonly>
                    </div>

                    <input type="hidden" name="application_type" id="application_type_id"
                        value="{{ $permit_application->application_type_id ?? '' }}">

                    <!-- ID Type -->
                    <div class="form-group mt-2 d-flex align-items-center">
                        <label for="identification_type_main" class="me-2 mb-0" style="width: 120px;">ID Type</label>
                        <select name="identification_type_id" id="identification_type_main" class="form-select">
                            <option value="" selected disabled>Select the ID present</option>
                            @if (strtolower($permit_application->occupation) === 'student')
                                @foreach ($id_types as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @else
                                @foreach ($id_types->where('id', '!=', 4) as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <!-- ID Number -->
                    <div class="form-group mt-2 d-flex align-items-center" id="id_number_container">
                        <label for="identification_number" class="me-2 mb-0" style="width: 120px;">ID Number</label>
                        <input type="text" name="identification_number" id="identification_number"
                            class="form-control">
                    </div>

                    <!-- Issue Date -->
                    <div class="form-group mt-2 d-flex align-items-center" id="issue_date_container">
                        <label for="issue_date" class="me-2 mb-0" style="width: 120px;">Issue Date</label>
                        <input type="date" id="issue_date" class="form-control" name="issue_date">
                    </div>

                    <!-- Expiry Date -->
                    <div class="form-group mt-2 d-flex align-items-center" id="expiry_date_container">
                        <label for="expiry_date" class="me-2 mb-0" style="width: 120px;">Expiry Date</label>
                        <input type="date" id="expiry_date" class="form-control" name="expiry_date">
                    </div>

                    <!-- Collected By -->
                    <div class="form-group mt-2 d-flex align-items-center">
                        <label for="collected_by" class="me-2 mb-0" style="width: 120px;">Collected By</label>
                        <input type="text" id="collected_by" class="form-control" name="collected_by">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="cardpickup" class="btn btn-success" id="cardpickupsubmit">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    const idType = document.getElementById("identification_type_main")
    const issueDate = document.getElementById("issue_date_container")
    const occupationInput = document.getElementById('occupation')
    if (idType) {
        if (occupationInput.value === "STUDENT") {
            issueDate.style.display = "none";
        } else {
            issueDate.style.display = "flex";
        }
    }
</script>

{{-- <script>
     const idTypeSelect = document.getElementById("identification_type_main");
    const idNumberContainer = document.getElementById("id_number_container");
    const occupationInput = document.getElementById("occupation");
    const issueDateContainer = document.getElementById("issue_date_container");
    const expiryDateContainer = document.getElementById("expiry_date_container");

    idTypeSelect.addEventListener("change", () => {
        idNumberContainer.style.display = idTypeSelect.value ? "flex" : "none";
    });

    // Hide issue/expiry date if occupation != STUDENT
    if (occupationInput && occupationInput.value.toUpperCase() !== "STUDENT") {
        issueDateContainer.style.display = "none";
        expiryDateContainer.style.display = "none";
    }
</script> --}}

{{-- <script>
    document.addEventListener("DOMContentLoaded",function(){
        const idTypeSelect = document.getElementById("identification_type_main");
    const idNumberContainer = document.getElementById("id_number_container");
    const occupationInput = document.getElementById("occupation");
    const issueDateContainer = document.getElementById("issue_date_container");
    const expiryDateContainer = document.getElementById("expiry_date_container");

    idTypeSelect.addEventListener("change", () => {
        idNumberContainer.style.display = idTypeSelect.value ? "flex" : "none";
    });

    // Hide issue/expiry date if occupation != STUDENT
    if (occupationInput && occupationInput.value.toUpperCase() == "STUDENT") {
        issueDateContainer.style.display = "none";
        expiryDateContainer.style.display = "none";
    }
    })
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("cardpickup");
    const submitBtn = document.getElementById("cardpickupsubmit");

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        submitBtn.disabled = true;
        submitBtn.textContent = "Saving...";

        const formData = new FormData(form);

        try {
            const response = await fetch("{{ route('collectedcards.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });

                const modal = bootstrap.Modal.getInstance(document.getElementById("cardModal"));
                modal.hide();
                form.reset();

                setTimeout(() => location.reload(), 2000);
            } 
            else if (response.status === 422) {
                const errors = data.errors;
                const messages = Object.values(errors).map(e => e[0]).join("<br>");
                Swal.fire({
                    icon: "error",
                    title: "Validation Error",
                    html: messages
                });
            } 
            else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: data.message || "An unexpected error occurred."
                });
            }

        } catch (error) {
            console.error("Error:", error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "A network or server error occurred."
            });
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = "Save changes";
        }
    });

   
});
</script> --}}

{{-- <script>
     // IMask for ID Number
    const collectedBy = document.getElementById('collected_by');
    const selectedIdType = document.getElementById('identification_type_main');
    const identificationNumber = document.getElementById('identification_number');
    let mask;

    selectedIdType.addEventListener('change', function() {
        let maskOptions;
        if (this.value === "1") {
            maskOptions = { mask: '000-000-000' }; // TRN example
            identificationNumber.placeholder = '___-___-___';
        } else {
            maskOptions = { mask: '0000000' };
            identificationNumber.placeholder = '_______';
        }
        if (mask) mask.destroy();
        mask = IMask(identificationNumber, maskOptions);
    });

    collectedBy.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });


</script> --}}
