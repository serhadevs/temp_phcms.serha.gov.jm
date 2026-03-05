<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <p>Good afternoon,</p>
    <p>Please see below for system generated daily 5:00p.m report.</p>

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
                                    ? 'Spanish Town Health Center'
                                    : ($user->facility_id == 2
                                        ? 'St. Thomas Health Center'
                                        : ($user->facility_id == 3
                                            ? 'Kingston and St.Andrew Health Center'
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

    <h4 style="text-decoration:underline">Today's Food Handlers Applications Breakdown</h4>
    <table id="users" class="" style="width:100%; border-collapse:collapse">
        <thead>
            <tr>
                <th style="text-align:start; border-style:solid">Category</th>
                <th style="text-align:start; border-style:solid">Number of Applications Entered</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permit_applications->groupBy('permitCategory.name') as $permit)
                <tr>
                    <td style="text-align:start; border-style:solid">{{ $permit->first()->permitCategory?->name }}</td>
                    <td style="text-align:start; border-style:solid">{{ $permit->count() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h4 style="text-decoration:underline">Other Application Types Breakdown</h4>
    <table id="users" class="" style="width:100%; border-collapse:collapse">
        <thead>
            <tr>
                <th style="text-align:start; border-style:solid">Application Type</th>
                <th style="text-align:start; border-style:solid">Number of Applications Entered</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:start; border-style:solid">Swimming Pool</td>
                <td style="text-align:start; border-style:solid">{{ $swimming_pool_count }}</td>
            </tr>
            <tr>
                <td style="text-align:start; border-style:solid">Establishment Clinic</td>
                <td style="text-align:start; border-style:solid">{{ $establishment_clinics_count }}</td>
            </tr>
            <tr>
                <td style="text-align:start; border-style:solid">Tourist Establishment Application</td>
                <td style="text-align:start; border-style:solid">{{ $tourist_application_count }}</td>
            </tr>
            <tr>
                <td style="text-align:start; border-style:solid">Food Establishment Applications</td>
                <td style="text-align:start; border-style:solid">{{ $establishment_applications_count }}</td>
            </tr>
        </tbody>
    </table>

    <h4 style="text-decoration:underline">Other System Operations Breakdown</h4>
    <table id="users" class="" style="width:100%; border-collapse:collapse">
        <thead>
            <tr>
                <th style="text-align:start; border-style:solid">System Operation Type</th>
                <th style="text-align:start; border-style:solid">Number Done</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:start; border-style:solid">Test Results</td>
                <td style="text-align:start; border-style:solid">{{ $test_results_count }}</td>
            </tr>
            <tr>
                <td style="text-align:start; border-style:solid">Application Sign Offs</td>
                <td style="text-align:start; border-style:solid">{{ $sign_off_count }}</td>
            </tr>
        </tbody>
    </table>

    <h4 style="text-decoration:underline">Overall Total Value Breakdown</h4>
    <table id="users" class="" style="width:100%; border-collapse:collapse">
        <thead>
            <tr>
                <th style="text-align:start; border-style:solid">Parish</th>
                <th style="text-align:start; border-style:solid">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align:start; border-style:solid">Kingston and St.Andrew</td>
                <td style="text-align:start; border-style:solid">
                    {{ (new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($total_ksa_payments, 'USD') }}
                </td>
            </tr>
            <tr>
                <td style="text-align:start; border-style:solid">St. Catherine</td>
                <td style="text-align:start; border-style:solid">
                    {{ (new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($total_stt_payments, 'USD') }}
                </td>
            </tr>
            <tr>
                <td style="text-align:start; border-style:solid">St. Thomas</td>
                <td style="text-align:start; border-style:solid">
                    {{ (new NumberFormatter('en_US', NumberFormatter::CURRENCY))->formatCurrency($total_sth_payments, 'USD') }}
                </td>
            </tr>
        </tbody>
    </table>
</body>

</html>
