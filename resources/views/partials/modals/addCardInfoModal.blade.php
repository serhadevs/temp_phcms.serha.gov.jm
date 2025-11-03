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
                        <select name="identification_type_id" id="identification_type_main" class="form-select"
                            >
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
                        <label for="issue_date" class="me-2 mb-0" style="width: 120px;" id="issue_date_label">Issue
                            Date</label>
                        <input type="date" id="issue_date" class="form-control" name="issue_date">
                    </div>

                    <!-- Expiry Date -->
                    <div class="form-group mt-2 mb-2 d-flex align-items-center" id="expiry_date_container">
                        <label for="expiry_date" class="me-2 mb-0" style="width: 120px;" id="expiry_date_label">Expiry
                            Date</label>
                        <input type="date" id="expiry_date" class="form-control" name="expiry_date">
                    </div>

                    <fieldset class="row mb-3">
                        <legend class="col-form-label col-sm-2 pt-0">Pick Up Type</legend>
                        <div class="col-sm-10">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pick_up_id" id="pick_up_id_1"
                                    value="1" checked>
                                <label class="form-check-label" for="pick_up">
                                    Self Pickup
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="pick_up_id" id="pick_up_id_2"
                                    value="2">
                                <label class="form-check-label" for="pick_up">
                                    Bearer
                                </label>
                            </div>
                            
                        </div>
                    </fieldset>


                    <!-- Collected By -->
                    <div class="form-group mt-2 d-flex align-items-center" id = "collectedCardContainer">
                        <label for="collected_by" class="me-2 mb-0" style="width: 120px;" id="collected_by_label">Collected By</label>
                        <input type="text" id="collected_by" class="form-control"  name="collected_by">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="cardpickup" class="btn btn-success" id="cardpickupsubmit">Save
                    changes</button>
            </div>
        </div>
    </div>
</div>



<script type="text/javascript">
    const occupationInput = document.getElementById('occupation');
    if (occupationInput && occupationInput.value == "STUDENT") {
        document.getElementById('issue_date').style.display = "none"
        document.getElementById('issue_date_label').style.display = "none"
        document.getElementById('expiry_date').style.display = "none"
        document.getElementById('expiry_date_label').style.display = "none"
    } else {
        document.getElementById('issue_date').style.display = "flex"
        document.getElementById('issue_date_label').style.display = "flex"
        document.getElementById('expiry_date').style.display = "flex"
        document.getElementById('expiry_date_label').style.display = "flex"
    }

</script>

<script>
    // IMask for ID Number
    const collectedBy = document.getElementById('collected_by');
    const selectedIdType = document.getElementById('identification_type_main');
    const identificationNumber = document.getElementById('identification_number');
    let mask;

    selectedIdType.addEventListener('change', function() {
        let maskOptions;
        if (this.value === "1") {
            maskOptions = {
                mask: '000-000-000'
            }; // TRN example
            identificationNumber.placeholder = '___-___-___';
        } else {
            maskOptions = {
                mask: '0000000'
            };
            identificationNumber.placeholder = '_______';
        }
        if (mask) mask.destroy();
        mask = IMask(identificationNumber, maskOptions);
    });

    collectedBy.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const form = document.getElementById("cardpickup");
        const submitBtn = document.getElementById("cardpickupsubmit");

        form.addEventListener("submit", async (event) => {
            event.preventDefault(); // Stop default form submission

            // Disable submit button while sending
            submitBtn.disabled = true;
            submitBtn.textContent = "Saving...";

            // Collect all form data
            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('collectedcards.store') }}", {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // ✅ Success
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Close modal and reset form
                    const modalEl = document.getElementById("cardModal");
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    modal.hide();

                    form.reset();

                    // Optionally reload page after short delay
                    setTimeout(() => location.reload(), 2000);

                } else if (response.status === 422) {
                    // ⚠️ Validation errors
                    const messages = Object.values(data.errors)
                        .map(err => err.join("<br>"))
                        .join("<br>");

                    Swal.fire({
                        icon: "error",
                        title: "Validation Error",
                        html: messages
                    });

                } else {

                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: data.message || "An unexpected error occurred."
                    });
                }

            } catch (error) {
                console.error("Error submitting form:", error);
                Swal.fire({
                    icon: "error",
                    title: "Network Error",
                    text: "Could not connect to the server. Please try again."
                });

            } finally {
                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = "Save changes";
            }
        });
    });
</script>
