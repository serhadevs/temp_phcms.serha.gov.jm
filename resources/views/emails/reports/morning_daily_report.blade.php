<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Good morning,</p>
    <p>Please see below for system generated daily 9:30a.m report.</p>

    <h4 style="text-decoration:underline">Today's Logged in Users</h4>
    <table id="users" class="" style="width:100%; border-collapse:collapse">
        <thead>
            <tr>
                <th style="text-align:start; border-style:solid">Firstname</th>
                <th style="text-align:start; border-style:solid">LastName</th>
                <th style="text-align:start; border-style:solid">Facility</th>
                <th style="text-align:start; border-style:solid">Role</th>
                <th style="text-align:start; border-style:solid">Email</th>
                <th style="text-align:start; border-style:solid">Last Seen</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td style="text-align:start; border-style:solid">{{ $user->firstname }}</td>
                    <td style="text-align:start; border-style:solid">{{ $user->lastname }}</td>
                    <td style="text-align:start; border-style:solid">
                        @php
                            $facilityName =
                                $user->facility_id == 1
                                    ? 'STC'
                                    : ($user->facility_id == 2
                                        ? 'STT'
                                        : ($user->facility_id == 3
                                            ? 'KSA'
                                            : 'Unknown Facility'));
                            echo $facilityName;
                        @endphp
                    </td style="text-align:start; border-style:solid">
                    <td style="text-align:start; border-style:solid">{{ $roles[$user->role_id] }}</td>
                    <td style="text-align:start; border-style:solid">{{ $user->email }}</td>
                    <td style="text-align:start; border-style:solid">
                        @if ($user->OnlineUser())
                            <span class="badge bg-success">Online</span>
                        @else
                            <span
                                class="badge bg-danger">{{ Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="text-decoration:underline">Database Status</h4>
    <p>Database Status : {{ $database_status ? 'Operational' : 'Needs Check' }}</p>
</body>

</html>
