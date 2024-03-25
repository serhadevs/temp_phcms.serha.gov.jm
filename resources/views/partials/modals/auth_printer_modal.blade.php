@foreach ($downloads as $download)
    <div class="modal fade" id="printer-modal-{{ $download->id }}" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-center" id="staticBackdropLabel">Enter your credentials</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="auth-form-{{ $download->id }}" method="POST" action="/downloads/package"
                    enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="modal-body">
                        <label for="" class="form-label">
                            <span class="fw-bold text-danger">*</span>
                            Email
                            <span class="text-danger mx-2" id="error-email-{{ $download->id }}"></span>
                        </label>

                        <input type="text" class="form-control" required name="email" id="email-{{ $download->id }}">
                        <label for="" class="form-label mt-3" required>
                            <span class="fw-bold text-danger">*</span>
                            Password
                            <span class="text-danger mx-2" id="error-password-{{ $download->id }}"></span>
                        </label>
                        <input type="password" class="form-control" required name="password" id="password-{{ $download->id }}">
                        <input type="text" name="download_id" value="{{ $download->id }}" class="form-control"
                            hidden>
                        <input type="text" class="form-control" value="{{ $download->application_type_id }}" name="application_type" hidden>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="close-modal-{{ $download->id }}">Close</button>
                        <button type="button" class="btn btn-success btn-sm"
                            onclick="submitAuth({{ json_encode($download->id) }})">Download Files</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach