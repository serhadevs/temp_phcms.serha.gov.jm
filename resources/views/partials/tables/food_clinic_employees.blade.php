<table class="table-striped table-bordered" style="width:100%">
    <thead>
        <tr>
            <th>Application ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Permit No.</th>
            <th>Signed Off Status</th>
            <th>Payment Status</th>
            <th>Photo Status</th>
            <th>Option</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($application->permit as $permit_application)
            <tr>
                <td>{{ $permit_application->id }}</td>
                <td>{{ $permit_application->firstname }}</td>
                <td>{{ $permit_application->lastname }}</td>
                <td>{{ $permit_application->permit_no }}</td>
                <td><span
                        class="badge text-bg-{{ $permit_application->sign_off_status == '1' ? 'success' : 'danger' }}">{{ $permit_application->sign_off_status == '1' ? 'COMPLETE' : 'INCOMPLETE' }}</span>
                </td>
                <td>
                    <span
                        class="badge text-bg-{{ empty($permit_application->payment) ? 'danger' : 'success' }}">{{ empty($permit_application->payment) ? 'Not Paid' : 'Paid' }}</span>
                    </span>
                </td>
                <td><span
                        class="badge text-bg-{{ $permit_application->photo_upload == '' ? 'danger' : 'success' }}">{{ $permit_application->photo_upload == '' ? 'No Image' : 'Uploaded' }}</span>
                </td>
                <td>
                    <a href="" class="btn btn-sm btn-primary">
                        View
                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
