<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHCMS - Onsite Verification Powered By ID Pro</title>
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
        <!-- Header Section -->
        <div class="page-header d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1">FONTANA PHARMACY LTD</h3>
                <p class="text-muted mb-0">Onsite Verification &middot; Application #3492</p>
            </div>
            <div>
                <span class="badge bg-warning text-dark fs-6"><i class="fa-solid fa-clock me-1"></i> Pending Sign-Off</span>
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
                            <div class="details-value">SHOP 23, SOVEREIGN CENTRE, 106 HOPE ROAD, KINGSTON 6</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Contact Person</div>
                            <div class="details-value">ANDRENE POWELL</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Telephone</div>
                            <div class="details-value">+1(876)978-3485</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Email Address</div>
                            <div class="details-value">N/A</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Fax</div>
                            <div class="details-value">N/A</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">No. of Employees</div>
                            <div class="details-value">21</div>
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
                            <div class="details-value">N/A</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Proposed Visit Date</div>
                            <div class="details-value">N/A</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Proposed Time</div>
                            <div class="details-value">9:00 AM</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="details-label">Sign-Off</div>
                            <div class="details-value text-danger">No sign-off has been recorded yet.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Permit Holders Table Section -->
        <div class="table-wrapper">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <h5 class="mb-0"><i class="fa-solid fa-users me-2"></i>Permit Holders (10)</h5>
                
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
                        <!-- Row 1 -->
                        <tr>
                            <td><div class="photo-circle">ASH</div></td>
                            <td><strong>KSA01040426</strong></td>
                            <td>
                                ASHLEY BUCKLEY<br>
                                <span class="address-text">LOT 343 HOUSING DRIVE KINGSTON 6</span>
                            </td>
                            <td>CASHIER</td>
                            <td>130-508-004</td>
                            <td>1(876)583-4936</td>
                            <td>Female</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-success status-badge">Granted Signed Off</span></td>
                        </tr>
                        <!-- Row 2 -->
                        <tr>
                            <td><div class="photo-circle">MAK</div></td>
                            <td><strong>KSA10110426</strong></td>
                            <td>
                                MAKADA FRANCIS<br>
                                <span class="address-text">527 ORLANDO AVENUE SPANISH TOWN</span>
                            </td>
                            <td>STOREROOM ASSISTANT</td>
                            <td>123-910-226</td>
                            <td>1(876)819-4897</td>
                            <td>Female</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-success status-badge">Granted Signed Off</span></td>
                        </tr>
                        <!-- Row 3 -->
                        <tr>
                            <td><div class="photo-circle">CEC</div></td>
                            <td><strong>KSA45150426</strong></td>
                            <td>
                                CECILE GORDON-HEMMINGS<br>
                                <span class="address-text">6 CIRCLE CLOSE TRAFALGAR PARK KINGSTON 10</span>
                            </td>
                            <td>PHARMACIST</td>
                            <td>100-067-409</td>
                            <td>1(876)288-8666</td>
                            <td>Female</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-warning text-dark status-badge">Pending Signed Off</span></td>
                        </tr>
                        <!-- Row 4 -->
                        <tr>
                            <td><div class="photo-circle">NIC</div></td>
                            <td><strong>KSA05720426</strong></td>
                            <td>
                                NICHOLE HEMMINGS<br>
                                <span class="address-text">3 YORO CRESCENT THREE OAKS GARDEN KINGSTON 20</span>
                            </td>
                            <td>PHARMACY TECHNICIAN</td>
                            <td>128-858-486</td>
                            <td>1(876)507-0335</td>
                            <td>Female</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-success status-badge">Granted Signed Off</span></td>
                        </tr>
                        <!-- Row 5 -->
                        <tr>
                            <td><div class="photo-circle">MAT</div></td>
                            <td><strong>KSA39100426</strong></td>
                            <td>
                                MATTHEW HITCHENER<br>
                                <span class="address-text">9 WOODLAWN AVENUE KINGSTON 19</span>
                            </td>
                            <td>WAREHOUSE SUPERVISOR</td>
                            <td>116-789-280</td>
                            <td>1(876)463-1008</td>
                            <td>Male</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-success status-badge">Granted Signed Off</span></td>
                        </tr>
                        <!-- Row 6 -->
                        <tr>
                            <td><div class="photo-circle">SAD</div></td>
                            <td><strong>KSA02190426</strong></td>
                            <td>
                                SADIAN MOULTON<br>
                                <span class="address-text">14 RHONA WALK DELACREE PARK KINGSTON 13</span>
                            </td>
                            <td>CASHIER</td>
                            <td>113-306-962</td>
                            <td>1(876)531-9255</td>
                            <td>Female</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-success status-badge">Granted Signed Off</span></td>
                        </tr>
                        <!-- Row 7 -->
                        <tr>
                            <td><div class="photo-circle">TRI</div></td>
                            <td><strong>KSA89920426</strong></td>
                            <td>
                                TRICHELLE NOYAN<br>
                                <span class="address-text">4 PAYTON PLACE MONA ROAD KINGSTON</span>
                            </td>
                            <td>CASHIER</td>
                            <td>130-351-105</td>
                            <td>1(658)218-9599</td>
                            <td>Female</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-success status-badge">Granted Signed Off</span></td>
                        </tr>
                        <!-- Row 8 -->
                        <tr>
                            <td><div class="photo-circle">RUT</div></td>
                            <td><strong>KSA41270426</strong></td>
                            <td>
                                RUTHANN PENGELLEY<br>
                                <span class="address-text">5 HAMPSTEAD PLACE KINGSTON 3</span>
                            </td>
                            <td>CASHIER</td>
                            <td>126-918-856</td>
                            <td>1(876)847-3831</td>
                            <td>Female</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-success status-badge">Granted Signed Off</span></td>
                        </tr>
                        <!-- Row 9 -->
                        <tr>
                            <td><div class="photo-circle">NIC</div></td>
                            <td><strong>KSA61450426</strong></td>
                            <td>
                                NICHOLAS SMITH<br>
                                <span class="address-text">25 COLLISTON CLOSE</span>
                            </td>
                            <td>INVENTORY ASSISTANT</td>
                            <td>129-376-701</td>
                            <td>1(876)570-5967</td>
                            <td>Male</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-warning text-dark status-badge">Pending Signed Off</span></td>
                        </tr>
                        <!-- Row 10 -->
                        <tr>
                            <td><div class="photo-circle">CHA</div></td>
                            <td><strong>KSA40850426</strong></td>
                            <td>
                                CHADANE THOMPSON<br>
                                <span class="address-text">GAYLE MOUNT DISTRICT GORDON TOWN ST ANDREW</span>
                            </td>
                            <td>SALES ASSOCIATE</td>
                            <td>129-984-248</td>
                            <td>1(876)565-1953</td>
                            <td>Male</td>
                            <td>N/A</td>
                            <td><span class="badge rounded-pill bg-success status-badge">Granted Signed Off</span></td>
                        </tr>
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

            searchInput.addEventListener("keyup", function(e) {
                const term = e.target.value.toLowerCase();
                let hasResults = false;

                tableRows.forEach(row => {
                    // Get all text content from the row
                    const rowText = row.textContent.toLowerCase();
                    
                    if (rowText.includes(term)) {
                        row.style.display = "";
                        hasResults = true;
                    } else {
                        row.style.display = "none";
                    }
                });

                // Toggle 'No Results' message
                if (!hasResults) {
                    noResults.classList.remove('d-none');
                } else {
                    noResults.classList.add('d-none');
                }
            });
        });
    </script>
</body>
</html>