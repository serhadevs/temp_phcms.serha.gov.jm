<div class="card">
    <div class="card-body">
        <table id="currentUsers" class="display table nowrap table-sm table-bordered" style="width:100%;max-width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Email</th>
                    <th>Last Seen</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($currentUsers as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->firstname }}</td>
                        <td>{{ $user->lastname }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->last_seen)
                                {{ Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}
                            @else
                                Unknown
                            @endif
                        </td>
                        <td>
                            @if (Cache::has('user-is-online-' . $user->id))
                                <span class="text-success">Online</span>
                            @else
                                <span class="text-secondary">Offline</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">


 <script>
     new DataTable('#currentUsers', {
         responsive: true,
         scrollX:true,
     });
 </script>