<div class="modal fade" id="cardModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Enter Pickup Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cardpickup">
                    @csrf
                 
                    <div class="form-group mt-2" style="display: flex; align-items: center;">
                        <label for="applicationnumber" style="margin-right: 10px;">Application Id</label>
                        <input type="text" id = "card_app_id" class="form-control" style="flex: 1;" disabled
                            name="app_id">
                    </div>

                    <div class="form-group mt-2" style="display: flex; align-items: center;">
                        <label for="applicationnumber" style="margin-right: 10px;">Collected By</label>
                        <input type="text" id = "collected_by" class="form-control" style="flex: 1;"
                            name="collected_by">
                    </div>

                    <div class="form-group mt-2" style="display: flex; align-items: center;">
                        <label for="applicationnumber" style="margin-right: 10px;">Application Type Id</label>
                        <input type="text" id = "application_type_id" class="form-control" style="flex: 1;" disabled
                            name="application_type">
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" id="cardpickupsubmit">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        var form = $('#cardpickup');

        $('#cardpickupsubmit').on('click', function() {

            var formData = {
                app_id: $('#card_app_id').val(),
                collected_by: $('#collected_by').val(),
                application_type: $('#application_type_id').val(),
                _token: form.find('input[name="_token"]').val()
            }

            
            $.ajax({
                url: "/collected-card/store",
                method: "POST",
                data: formData,
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    if (response.success) {
                        $('#cardModal').modal('hide');
                        alert(response.message);
                    } else {
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error occurred:', xhr.responseText);
                    alert('There was an error saving the pickup details: ' + (xhr
                        .responseJSON ? xhr.responseJSON.message : error));
                }
            });
        })
    })
</script>
