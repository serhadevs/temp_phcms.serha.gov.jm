<div class="row g-3 mt-2 mb-2">
    <table id="applications" class="display table nowrap table-sm table-bordered" style="width:100%;max-width:100%">
        <thead>
            <tr>
                <th><input type="checkbox" name="selectedCheckbox" id=""></th>
                <th>View</th>
                <th>Status</th>
                <th>Application No.</th>
                <th>Establishment</th>
                <th>Permit #</th>
                <th>FirstName</th>
                <th>MiddleName</th>
                <th>LastName</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($applications as $application)
                <tr>
                    <th><input type="checkbox" name="status" id=""></th>
                    <td><button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        View
                      </button></td>
                    <td>{{ $application->sign_off_status }}</td>
                    <td>{{ $application->id }}</td>
                    <td>{{ $application->est_name}}</td>
                    <td>{{ $application->permit_no }}</td>
                    <td>{{ $application->permit_firstname }}</td>
                    <td>{{ $application->permit_middlename }}</td>
                    <td>{{ $application->permit_lastname }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div>
      <button class="btn btn-primary"> <i class="bi bi-box-arrow-in-right"></i> Approve</button>
    </div>
    
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              ...
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </div>
    
   <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
   <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
   <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script>
        new DataTable('#applications', {
            responsive: true,
        });
    </script>

    