<div class="modal fade" id="exampleModal" tabindex="-1"
aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h1 class="modal-title fs-5" id="modalHeader">
            </h1>
            <button type="button" class="btn-close"
                data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date Sent</th>
                        <th>Sent By</th>
                        {{-- <th>Resend</th> --}}
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td id="message_type"></td>
                        <td id="status"></td>
                        <td id="sent_by"></td>
                        <td id="sent_at"></td>
                       
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary"
                data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>
</div>