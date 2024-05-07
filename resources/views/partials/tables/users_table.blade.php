<div class="card shadow">
    <div class="card-body">
        <div class="row g-3 mt-2 mb-2">
            <table id="users" class="display table nowrap table-sm table-bordered" style="width:100%;max-width:100%">
                <thead>
                    <tr>
                   <th>Statue</th>
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
                            <td><a href = "{{ route('/settings/users/deactivate/{id}') }}">Active</a></td>
                            <td>{{ $user->firstname}}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>
                                @php
                                    $facilityName = $user->facility_id == 1 ? "STC" : ($user->facility_id == 2 ? "STT" : ($user->facility_id == 3 ? "KSA" : "Unknown Facility"));
                                    echo $facilityName;
                               @endphp 
                            </td>
                            <td>{{ ($user->name) }}</td>
                            <td>{{ $user->telephone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->created_at)->format('F d Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('F d Y') }}</td> 
                            <td>
                                <a href="{{ route('users.edit', ['id' => $user->id]) }}" class="btn btn-sm btn-primary">Edit</a>
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
    