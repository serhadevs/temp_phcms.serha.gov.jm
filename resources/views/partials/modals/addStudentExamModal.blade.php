<div class="modal fade" id="addStudentExamModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    {{ isset($item->id) ? 'Edit Exam' : 'Add Student Exam' }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="studentExamForm" action="{{ route('exams.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="form_method" name="_method" value="POST">
                    <input type="hidden" name="exam_id" id="exam_id" value="">
                    <div class="mb-3">
                        <label for="title" class="form-label">Exam Name</label>
                        <input type="text"
                            class="form-control @error('title')
                    is-invalid
                @enderror"
                            id="title" name = "title" placeholder="Exam Title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('addStudentExamModal');
        const form = document.getElementById('studentExamForm');
        const methodInput = document.getElementById('form_method');

        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            const id = button.getAttribute('data-id');
            const title = button.getAttribute('data-title');

            // Populate form fields
            form.querySelector('#exam_id').value = id || '';
            form.querySelector('#title').value = title || '';

            // Adjust form action + method for update
            if (id) {
                form.action = `/exams/update/${id}`; // Assuming resource route
                methodInput.value = 'POST';

                modal.querySelector('.modal-title').textContent = 'Edit Exam';
            } else {
                form.action = `{{ route('exams.store') }}`;
                methodInput.value = 'POST';

                modal.querySelector('.modal-title').textContent = 'Add Student Exam';
            }
        });
    });
</script>
