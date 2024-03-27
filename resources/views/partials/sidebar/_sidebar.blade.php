<aside id="sidebar">
    <div class="d-flex">
        <button class="toggle-btn" type="button">
            <i class="lni lni-grid-alt"></i>
        </button>
        <div class="sidebar-logo">
            <a href="/dashboard">PHCMS</a>
        </div>
    </div>

    <ul class="sidebar-nav">
        <li class="sidebar-item">
            <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#permit" aria-expanded="false" aria-controls="permit">
                <i class="lni lni-user"></i>
                <span>Food Handlers Permit</span>
            </a>
            <ul id="permit" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/permit/filter/0" class="sidebar-link">Applications</a>
                </li>
                <li class="sidebar-item">
                    <a href="/permit/application" class="sidebar-link">Create New</a>
                </li>
            </ul>
        </li>

        <li class="sidebar-item">
            <a href="/sign-off" class="sidebar-link">
                <i class="bi bi-clipboard-check"></i>
                <span>Signoff</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/advance-search/create" class="sidebar-link">
                <i class="lni lni-search-alt"></i>
                <span>Advance Search</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/payments/cancellations" class="sidebar-link">
                <i class="bi bi-slash-circle"></i>
                <span>Payment Cancel Requests</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/food-establishments/filter/0" class="sidebar-link">
                <i class="bi bi-hospital"></i>
                <span>Food Establishments</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                <i class="bi bi-coin"></i>
                <span>Payment</span>
            </a>
            <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/payments/index/filter/0" class="sidebar-link">Processed Payments</a>
                </li>
                <li class="sidebar-item">
                    <a href="/payments/applications/filter/0" class="sidebar-link">Outstanding Applications</a>
                </li>
                <li class="sidebar-item">
                    <a href="/payments/create" class="sidebar-link">Create New</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#test-center" aria-expanded="false" aria-controls="test-center">
                <i class="bi bi-file-earmark-medical"></i>
                <span>Test Center</span>
            </a>
            <ul id="test-center" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/test-center/test-results/permit/filter/0" class="sidebar-link">Food Handlers Results</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#health_interview" aria-expanded="false" aria-controls="health_interview">
                <i class="bi bi-person-lines-fill"></i>
                <span>Health Interview</span>
            </a>
            <ul id="health_interview" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/health-interview/filter/0" class="sidebar-link">Processed Interviews</a>
                </li>
                <li class="sidebar-item">
                    <a href="/health-interview/outstanding/filter/1/0" class="sidebar-link">Outstanding Food
                        Handlers</a>
                </li>
                <li class="sidebar-item">
                    <a href="/health-interview/outstanding/filter/2/0" class="sidebar-link">Outstanding Health Cert.</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#downloads" aria-expanded="false" aria-controls="reports">
                <i class="bi bi-cloud-arrow-down"></i>
                <span>Downloads</span>
            </a>
            <ul id="downloads" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/downloads/foodhandlers/filter/0" class="sidebar-link">Food Handlers Permits</a>
                </li>
                <li class="sidebar-item">
                    <a href="/downloads/food-establishments" class="sidebar-link">Food Establishments</a>
                </li>
                <li class="sidebar-item">
                    <a href="/downloads/food-establishments/filter/0" class="sidebar-link">Tourist Establishments</a>
                </li>
            </ul>
        </li>
        {{-- Multiple level dropdown --}}
        {{-- <li class="sidebar-item">
            <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#downloads2" aria-expanded="false" aria-controls="reports">
                <i class="bi bi-cloud-arrow-down"></i>
                <span>Testing</span>
            </a>
            <ul id="downloads2" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#download3" aria-expanded="false" aria-controls="reports">
                        <span>Downloads</span>
                    </a>
                    <ul id="download3" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#download2">
                        <li class="sidebar-item">
                            <a href="/downloads/foodhandlers/filter/0" class="sidebar-link">Food Handlers Permits</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="/downloads/food-establishments" class="sidebar-link">Food Establishments</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="/downloads/food-establishments/filter/0" class="sidebar-link">Tourist
                                Establishments</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="/downloads/food-establishments" class="sidebar-link">Food Establishments</a>
                </li>
                <li class="sidebar-item">
                    <a href="/downloads/food-establishments/filter/0" class="sidebar-link">Tourist Establishments</a>
                </li>
            </ul>
        </li> --}}
        <li class="sidebar-item">
            <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#reports" aria-expanded="false" aria-controls="reports">
                <i class="bi bi-journal-check"></i>
                <span>Reports</span>
            </a>
            <ul id="reports" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/report/payment" class="sidebar-link">End of Day Report</a>
                </li>
            </ul>
        </li>
        <li class="sidebar-item">
            <a href="#" class="sidebar-link">
                <i class="lni lni-cog"></i>
                <span>Setting</span>
            </a>
        </li>
    </ul>
</aside>

<script>
    $(document).ready(function() {
        $('#reports').on('hide.bs.collapse', function() {
            // Handle the collapse event here
            console.log('Sidebar collapsed');
        });
    });
</script>
