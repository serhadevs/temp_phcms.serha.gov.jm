<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHCMS - Onsite Verification</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #0d6efd;
            --bg-color: #f4f6f9;
        }
        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 1px;
        }
        .page-header {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            margin-bottom: 20px;
            border-left: 5px solid var(--primary-color);
        }
        .details-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            padding: 20px;
            margin-bottom: 20px;
            height: 100%;
        }
        .details-label {
            font-size: 0.85rem;
            color: #6c757d;
            margin-bottom: 2px;
            text-transform: uppercase;
            font-weight: 600;
        }
        .details-value {
            font-size: 1rem;
            font-weight: 500;
            color: #212529;
            margin-bottom: 15px;
        }
        .table-wrapper {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            padding: 20px;
        }
        .photo-circle {
            width: 40px;
            height: 40px;
            background-color: #e9ecef;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--primary-color);
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 10px;
        }
        .search-container {
            position: relative;
        }
        .search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }
        .search-input {
            padding-left: 40px;
            border-radius: 20px;
        }
        .address-text {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-notes-medical me-2"></i>PHCMS</a>
            <div class="d-flex">
                <a href="#" class="btn btn-outline-light btn-sm">Login</a>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 pb-5">
        
        <!-- Flash Error Message from Controller -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Header Section -->
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1">{{ $onsite->name ?? $onsite->establishment_name ?? 'Unknown Establishment' }}</h3>
                <p class="text-muted mb-0">Onsite Verification &middot; Application #{{ $onsite->id ?? 'N/A' }}</p>
            </div>
            <div>
                @if($onsite->signOff)
                    <span class="badge bg-success fs-6"><i class="fa-solid fa-check-double me-1"></i> Signed Off</span>
                @else
                    <span class="badge bg-warning text-dark fs-6"><i class="fa-solid fa-clock me-1"></i> Pending Sign-Off</span>
                @endif
            </div>
        </div>

        <!-- Details Section -->
        <div class="row mb-4">
            <!-- Establishment Details -->
            <div class="col-md-6 mb-3 mb-md-0">
                <div class="details-card">
                    <h5 class="border-bottom pb-2 mb-3"><i class="fa-solid fa-building me-2"></i>Establishment Details</h5>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="details-label">Address</div>
                            <div class="details-value">{{ $onsite->address ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Contact Person</div>
                            <div class="details-value">{{ $onsite->contact_person ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Telephone</div>
                            <div class="details-value">{{ $onsite->telephone ?? $onsite->phone ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Email Address</div>
                            <div class="details-value">{{ $onsite->email_address ?? $onsite->email ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Fax</div>
                            <div class="details-value">{{ $onsite->fax ?? 'N/A' }}</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">No. of Employees</div>
                            <div class="details-value">{{ $onsite->no_of_employees ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visit Details -->
            <div class="col-md-6">
                <div class="details-card">
                    <h5 class="border-bottom pb-2 mb-3"><i class="fa-solid fa-calendar-check me-2"></i>Visit Information</h5>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="details-label">Application Date</div>
                            <div class="details-value">
                                {{ $onsite->application_date ? \Carbon\Carbon::parse($onsite->application_date)->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Proposed Visit Date</div>
                            <div class="details-value">
                                {{ $onsite->proposed_visit_date ? \Carbon\Carbon::parse($onsite->proposed_visit_date)->format('M d, Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Proposed Time</div>
                            <div class="details-value">
                                {{ $onsite->proposed_time ? \Carbon\Carbon::parse($onsite->proposed_time)->format('h:i A') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Sign-Off</div>
                            <div class="details-value">
                                @if($onsite->signOff)
                                    <span class="text-success"><i class="fa-solid fa-check-circle"></i> Signed Off On {{ \Carbon\Carbon::parse($onsite->signOff->created_at)->format('M d, Y') }}</span>
                                @else
                                    <span class="text-danger">No sign-off has been recorded yet.</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permit Holders Table Section -->
        <div class="table-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h5 class="mb-0"><i class="fa-solid fa-users me-2"></i>Permit Holders ({{ $onsite->permits ? $onsite->permits->count() : 0 }})</h5>
                
                <!-- Search Input -->
                <div class="search-container w-25 min-w-200">
                    <i class="fa-solid fa-search"></i>
                    <input type="text" id="tableSearch" class="form-control search-input" placeholder="Search permit holders...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="permitTable">
                    <thead class="table-light">
                        <tr>
                            <th>Photo</th>
                            <th>Permit No.</th>
                            <th>Name & Address</th>
                            <th>Occupation</th>
                            <th>TRN</th>
                            <th>Contact</th>
                            <th>Gender</th>
                            <th>DOB</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($onsite->permits as $permit)
                        <tr>
                            <td>
                                <!-- Take first 3 letters of the name for the circle placeholder -->
                                <div class="photo-circle">{{ Str::limit($permit->name ?? $permit->first_name, 3, '') }}</div>
                            </td>
                            <td><strong>{{ $permit->permit_no ?? 'N/A' }}</strong></td>
                            <td>
                                {{ $permit->name ?? ($permit->first_name . ' ' . $permit->last_name) }}<br>
                                <span class="address-text">{{ $permit->address ?? 'N/A' }}</span>
                            </td>
                            <td>{{ $permit->occupation ?? 'N/A' }}</td>
                            <td>{{ $permit->trn ?? 'N/A' }}</td>
                            <td>{{ $permit->contact ?? $permit->phone ?? 'N/A' }}</td>
                            <td>{{ ucfirst($permit->gender) ?? 'N/A' }}</td>
                            <td>
                                {{ $permit->dob ? \Carbon\Carbon::parse($permit->dob)->format('M d, Y') : 'N/A' }}
                            </td>
                            <td>
                                @if(in_array(strtolower($permit->status), ['granted signed off', 'granted', 'active', 'approved']))
                                    <span class="badge rounded-pill bg-success status-badge">{{ $permit->status }}</span>
                                @else
                                    <span class="badge rounded-pill bg-warning text-dark status-badge">{{ $permit->status ?? 'Pending Signed Off' }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                No permit holders found for this establishment.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <!-- No Results Message (Hidden by default) -->
                <div id="noResults" class="text-center py-4 d-none text-muted">
                    <i class="fa-solid fa-magnifying-glass fs-2 mb-2"></i>
                    <p>No permit holders match your search.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Table Search Script -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById("tableSearch");
            const tableRows = document.querySelectorAll("#permitTable tbody tr");
            const noResults = document.getElementById("noResults");

            // Don't initialize search if table is empty
            if(tableRows.length === 1 && tableRows[0].querySelector('td').colSpan === 9) {
                searchInput.disabled = true;
                return;
            }

            searchInput.addEventListener("keyup", function(e) {
                const term = e.target.value.toLowerCase();
                let hasResults = false;

                tableRows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    
                    if (rowText.includes(term)) {
                        row.style.display = "";
                        hasResults = true;
                    } else {
                        row.style.display = "none";
                    }
                });

                if (!hasResults && term !== "") {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }
            });
        });
    </script>
</body>
</html>