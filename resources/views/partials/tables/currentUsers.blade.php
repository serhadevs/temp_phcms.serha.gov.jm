<table class="table table-bordered data-table">
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
