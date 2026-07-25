@extends('layouts.app')

@section('content')
    @include('partials.successMessage')

    <div class="container-xl">
        <div class="nk-content-inner">
            <div class="nk-content-body">

                {{-- Header --}}
                <div class="nk-block-head nk-page-head">
                    <div class="nk-block-head-between flex-wrap gap-2">
                        <div class="nk-block-head-content">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <a href="{{ url()->previous() }}" class="btn btn-icon btn-outline-light">
                                    <em class="icon ni ni-arrow-left"></em>
                                </a>
                                <h2 class="display-6 mb-0">{{ $onsite->name }}</h2>
                            </div>
                            <div class="text-soft">
                                Onsite Verification &middot; Application #{{ $onsite->id }}
                            </div>
                        </div>

                        <div>
                            @if ($onsite->sign_off && $onsite->sign_off->is_granted)
                                <span class="badge bg-success-soft text-success fs-6 px-3 py-2">
                                    <em class="icon ni ni-check-circle-fill me-1"></em> Signed Off &mdash; Granted
                                </span>
                            @elseif ($onsite->sign_off && !$onsite->sign_off->is_granted)
                                <span class="badge bg-danger-soft text-danger fs-6 px-3 py-2">
                                    <em class="icon ni ni-cross-circle-fill me-1"></em> Signed Off &mdash; Refused
                                </span>
                            @else
                                <span class="badge bg-warning-soft text-warning fs-6 px-3 py-2">
                                    <em class="icon ni ni-clock me-1"></em> Pending Sign-Off
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Establishment + Sign-off summary cards --}}
                <div class="row g-gs mb-2">
                    <div class="col-lg-8">
                        <div class="card shadow-none h-100">
                            <div class="card-header bg-lighter">
                                <h6 class="mb-0"><em class="icon ni ni-building me-1"></em> Establishment Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="text-soft small">Address</div>
                                        <div class="fw-medium">{{ $onsite->address ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-soft small">Telephone</div>
                                        <div class="fw-medium">{{ $onsite->telephone ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-soft small">Fax</div>
                                        <div class="fw-medium">{{ $onsite->fax_no ?? 'N/A' }}</div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="text-soft small">Contact Person</div>
                                        <div class="fw-medium">{{ $onsite->contact_person ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-soft small">Email Address</div>
                                        <div class="fw-medium">{{ $onsite->email_address ?? 'N/A' }}</div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-soft small">No. of Employees</div>
                                        <div class="fw-medium">{{ $onsite->no_of_employees ?? 'N/A' }}</div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="text-soft small">Application Date</div>
                                        <div class="fw-medium">
                                            {{ optional($onsite->application_date)->format('d F Y') ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-soft small">Proposed Visit Date</div>
                                        <div class="fw-medium">
                                            {{ optional($onsite->proposed_date)->format('d F Y') ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="text-soft small">Proposed Time</div>
                                        <div class="fw-medium">
                                            {{ $onsite->proposed_time ? \Carbon\Carbon::parse($onsite->proposed_time)->format('g:i A') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card shadow-none h-100">
                            <div class="card-header bg-lighter">
                                <h6 class="mb-0"><em class="icon ni ni-shield-check me-1"></em> Sign-Off</h6>
                            </div>
                            <div class="card-body">
                                @if ($onsite->sign_off)
                                    <div class="mb-3">
                                        <div class="text-soft small">Sign-Off Permit No.</div>
                                        <div class="fw-medium">{{ $onsite->sign_off->permit_no ?? 'N/A' }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="text-soft small">Sign-Off Date</div>
                                        <div class="fw-medium">
                                            {{ optional($onsite->sign_off->sign_off_date)->format('d F Y') ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="text-soft small">Expiry Date</div>
                                        <div class="fw-medium">
                                            {{ optional($onsite->sign_off->expiry_date)->format('d F Y') ?? 'N/A' }}
                                        </div>
                                    </div>
                                    @if (!$onsite->sign_off->is_granted && $onsite->sign_off->refusal_reason)
                                        <div>
                                            <div class="text-soft small">Refusal Reason</div>
                                            <div class="fw-medium text-danger">{{ $onsite->sign_off->refusal_reason }}</div>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-muted text-center py-4">
                                        <em class="icon ni ni-info fs-3 d-block mb-2"></em>
                                        No sign-off has been recorded yet.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Permits table --}}
                <div class="nk-block">
                    <div class="card shadow-none">
                        <div class="card-header bg-lighter d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">
                                <em class="icon ni ni-users me-1"></em>
                                Permit Holders
                                <span class="badge bg-primary-soft text-primary ms-1">{{ $onsite->permits->count() }}</span>
                            </h6>
                        </div>

                        <div class="card-body">
                            <table
                                id="permitsTable"
                                class="table table-hover align-middle"
                                data-toggle="table"
                                data-search="true"
                                data-show-columns="true"
                                data-show-toggle="true"
                                data-pagination="true"
                                data-page-size="10"
                                data-page-list="[10, 25, 50, 100, All]"
                                data-sort-name="name"
                                data-sort-order="asc"
                            >
                                <thead class="table-light">
                                    <tr>
                                        <th data-field="photo" data-sortable="false">Photo</th>
                                        <th data-field="permit_no" data-sortable="true">Permit No.</th>
                                        <th data-field="name" data-sortable="true">Name</th>
                                        <th data-field="occupation" data-sortable="true">Occupation</th>
                                        <th data-field="trn" data-sortable="true">TRN</th>
                                        <th data-field="contact" data-sortable="false">Contact</th>
                                        <th data-field="gender" data-sortable="true">Gender</th>
                                        <th data-field="dob" data-sortable="true">Date of Birth</th>
                                        <th data-field="status" data-sortable="false">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($onsite->permits as $permit)
                                        <tr>
                                            <td>
                                                @if ($permit->photo_upload && Storage::disk('public')->exists($permit->photo_upload))
                                                    <img src="{{ Storage::url($permit->photo_upload) }}"
                                                         alt="{{ $permit->firstname }}"
                                                         class="rounded-circle border"
                                                         style="width:42px;height:42px;object-fit:cover;">
                                                @else
                                                    <span class="rounded-circle bg-lighter d-inline-flex align-items-center justify-content-center border"
                                                          style="width:42px;height:42px;">
                                                        <em class="icon ni ni-user text-soft"></em>
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ $permit->permit_no }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-medium">
                                                    {{ trim($permit->firstname . ' ' . $permit->middlename . ' ' . $permit->lastname) }}
                                                </div>
                                                <div class="text-soft small">{{ $permit->address }}</div>
                                            </td>
                                            <td>{{ $permit->occupation ?? 'N/A' }}</td>
                                            <td>{{ $permit->trn ?? 'N/A' }}</td>
                                            <td>
                                                <div>{{ $permit->cell_phone ?? 'N/A' }}</div>
                                            </td>
                                            <td class="text-capitalize">{{ $permit->gender ?? 'N/A' }}</td>
                                            <td>
                                                {{ optional($permit->date_of_birth)->format('d M Y') ?? 'N/A' }}
                                            </td>
                                            <td>
                                                @if ($permit->granted)
                                                    <span class="badge bg-success-soft text-success">Granted</span>
                                                @elseif (!is_null($permit->granted))
                                                    <span class="badge bg-danger-soft text-danger">Refused</span>
                                                @else
                                                    <span class="badge bg-warning-soft text-warning">Pending</span>
                                                @endif

                                                @if ($permit->sign_off_status)
                                                    <span class="badge bg-info-soft text-info">Signed Off</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('styles')
        {{-- Bootstrap Table (bootstrap-table.com) — self-contained include, not used elsewhere in the app --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.css">

        <style>
            .bg-success-soft { background-color: rgba(29, 233, 182, 0.12); }
            .bg-danger-soft  { background-color: rgba(230, 84, 84, 0.12); }
            .bg-warning-soft { background-color: rgba(255, 171, 0, 0.12); }
            .bg-info-soft    { background-color: rgba(9, 187, 213, 0.12); }
            .bg-primary-soft { background-color: rgba(101, 118, 255, 0.12); }
            .bg-lighter      { background-color: #f7f8fa; }

            #permitsTable td,
            #permitsTable th {
                vertical-align: middle;
            }

            /* Bootstrap Table ships its own toolbar/pagination markup —
               these tweaks just tighten it up to match the rest of the card UI */
            .bootstrap-table .fixed-table-toolbar {
                margin-bottom: 12px;
            }

            .bootstrap-table .fixed-table-toolbar .search input {
                border-radius: 8px;
            }

            .bootstrap-table .fixed-table-pagination .pagination {
                margin-bottom: 0;
            }
        </style>
    @endpush

    @push('scripts')
        {{-- Bootstrap Table JS — progressively enhances the table via data-toggle="table" --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap-table@1.23.5/dist/bootstrap-table.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                $('#permitsTable').bootstrapTable({
                    formatNoMatches: function () {
                        return 'No permit holders found for this establishment.';
                    },
                });
            });
        </script>
    @endpush
@endsection