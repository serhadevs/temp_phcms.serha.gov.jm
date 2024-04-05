@extends('partials.layouts.layout')

@section('title', 'Search Food Handlers Permits')

@section('content')
    @include('partials.sidebar._sidebar')

    <div class="main">
        @include('partials.navbar._navbar')
        <div class="container">
            <h2>Search Food Handlers Permits</h2>
            <div class="card shadow my-2">
                <div class="card-header">Results</div>
                <div class="card-body">
                    <table id="example" class="table table-striped nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>App#</th>
                                <th>Permit No.</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Permit Type</th>
                                <th>Establishment</th>
                                <th>Category</th>
                                <th>Payment Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permits as $permit)
                                <tr>
                                    <td>
                                        @if (!empty($permit->photo_upload))
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal{{ $permit->id }}">Photo</button>
                                        @else
                                            <span class="badge text-bg-danger">MISSING</span>
                                        @endif
                                    </td>
                                    <td>{{ $permit->id }}</td>
                                    <td><b>{{ $permit->permit_no }}</b></td>
                                    <td>{{ $permit->firstname }}</td>
                                    <td>{{ $permit->middlename }}</td>
                                    <td>{{ $permit->lastname }}</td>
                                    <td>{{ ucfirst($permit->permit_type) }}</td>
                                    <td>{{ $permit->est_clinic_name }}</td>
                                    <td>{{ $permit->permit_category_name }}</td>
                                    <td>{{ $permit->receipt_no ? 'PAID' : 'NOT PAID' }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer">
                    @if (url()->previous() == url('/advance-search/create'))
                        <a href="{{ url('/advance-search/create') }}" class="btn btn-danger">Back</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @foreach ($permits as $permit)
        <!-- Modal -->
        <div class="modal fade" id="exampleModal{{ $permit->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $permit->firstname }}
                            {{ $permit->lastname }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @if ($permit->photo_upload)
                            <img src={{ $permit->photo_upload }} alt="No Image found"
                                style="display:block" class="mx-auto rounded w-100">
                        @endif
                        @if (!$permit->photo_upload)
                            @if (strtolower($permit->gender) == 'male')
                                <img src="{{ asset('images/male.jpg') }}" class="w-100 rounded-circle" />
                            @endif
                            @if (strtolower($permit->gender) == 'female')
                                <img src="{{ asset('images/female.jpg') }}" class="w-100 rounded-circle" />
                            @endif

                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                scrollX: true,
            });
        });
    </script>
@endsection
