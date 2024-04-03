<div class="card">
    <div class="card-header">
        List of users logged in
    </div>
    <div class="card-body">
        <table id="loginUsers" class="display table nowrap table-sm table-bordered" style="width:100%;max-width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Firstname</th>
                    <th>Lastname</th>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                    <th>Ip Address</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($loginUsers as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->firstname }}</td>
                        <td>{{ $user->lastname }}</td>
                        <td>{{ $user->login_time }}</td>
                        <td>
                            {{ $user->logout_time }}
                        </td>
                        <td>{{ $user->ip_address }}</td>
                        <td>
                            @if ($user->logout_time)
                            <span class="badge text-bg-danger">Offline</span>
                            @else
                            <span class="badge text-bg-success">Online</span>
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
    new DataTable('#loginUsers', {
        responsive: true,
        scrollX: true,
    });
</script>
