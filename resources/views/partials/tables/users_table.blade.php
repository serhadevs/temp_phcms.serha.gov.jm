<div class="card shadow">
    <div class="card-body">
        <div class="row g-3 mt-2 mb-2">
            <table id="users" class="display table nowrap table-sm table-bordered" style="width:100%;max-width:100%">
                <thead>
                    <tr>
                   
                     <th>Firstname</th>
                     <th>LastName</th>
                     <th>Facility</th>
                     <th>Role</th>
                     <th>Telephone</th>
                     <th>Email</th>
                     <th>Date Added</th>
                     <th>Last Updated</th>
                     <th>Options</th>
                    </tr>
                </thead>
                <tbody>
        
                    @foreach ($users as $user)
                        <tr>
                            
                            <td>{{ $user->firstname}}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>
                                {{ $user->facility_id = 1 }}
                            </td>
                            <td>{{ $user->role_id }}</td>
                            <td>{{ $user->telephone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at }}</td>
                            <td>{{ $user->updated_at }}</td> 
                            <td>
                                <button class="btn btn-sm btn-primary">Edit</button>
                                <a href ="/settings/users/reset-password/{{ $user->id }}" class="btn btn-sm btn-primary">Reset</a>
                                <a href ="/settings/users/restore/{{ $user->id }}"class="btn btn-sm btn-primary">Restore</button>
        
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

 <script>
     new DataTable('#users', {
         responsive: true,
         scrollX:true,
     });
 </script>
    