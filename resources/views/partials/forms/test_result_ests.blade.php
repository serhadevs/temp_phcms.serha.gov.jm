<form action="">
    <form action="">
        @if ($app_type_id == '3')
            <div class="mt-3">
                <label for="" class="form-label">Purpose of Visit</label>
                <select name="" id="" class="form-control">
                    <option value="">Select Visit Purpose</option>
                    <option value="routine">Routine</option>
                    <option value="compliance">Compliance</option>
                    <option value="reinspection">Re-inspection</option>
                    <option value="complaint">Complaint</option>
                </select>
            </div>
        @endif
        <div class="row mt-3">
            <div class="col">
                <label for="" class="form-label">Name of all Inspectors</label>
                <input type="text" class="form-control" placeholder="Separate each name with a comma">
            </div>
            <div class="col">
                <label for="" class="form-label">Inspection Location</label>
                <input type="text" class="form-control"
                    value="{{ $app_type_id == '5' ? $application->swimming_pool_address : $application->establishment_address }}">
            </div>
        </div>
        <div class="mt-3">
            <label for="" class="form-label">Date of Inspection</label>
            <input type="text" class="form-control">
        </div>
        <div class="row mt-3">
            <div class="col">
                <label for="" class="form-label">Critical Score</label>
                <input type="text" class="form-control" placeholder="">
            </div>
            <div class="col">
                <label for="" class="form-label">Overall Score</label>
                <input type="text" class="form-control">
            </div>
        </div>
        <div class="mt-3">
            <label for="" class="form-label">Comments</label>
            <textarea class="form-control">

            </textarea>
        </div>
        <button class="btn btn-primary mt-3">
            Submit
        </button>
    </form>
</form>
