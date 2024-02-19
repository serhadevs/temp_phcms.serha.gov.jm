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
                <i class="lni lni-agenda"></i>
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
            <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                <i class="lni lni-protection"></i>
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
