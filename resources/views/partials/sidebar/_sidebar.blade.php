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
            <a href="/food-handlers-clinics/filter/0" class="sidebar-link">
                <i class="bi bi-buildings"></i>
                <span>Food Handlers Clinics</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/barber-cosmet/filter/0" class="sidebar-link">
                <i class="bi bi-scissors"></i>
                <span>Barber/Cosmet etc.</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/tourist-establishments/filter/00" class="sidebar-link">
                <i class="bi bi-luggage"></i>
                <span>Tourist Establishments</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/swimming-pools/filter/0" class="sidebar-link">
                <i class="bi bi-water"></i>
                <span>Swimming Pools</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/appointments" class="sidebar-link">
                <i class="lni lni-calendar"></i>
                <span>Appointments</span>
            </a>
        </li>
        @if (in_array(auth()->user()->role_id, [1, 5, 7]))
            <li class="sidebar-item">
                <a href="/sign-off" class="sidebar-link">
                    <i class="bi bi-clipboard-check"></i>
                    <span>Signoff</span>
                </a>
            </li>
        @endif
        <li class="sidebar-item">
            <a href="/advance-search/create" class="sidebar-link">
                <i class="lni lni-search-alt"></i>
                <span>Advance Search</span>
            </a>
        </li>




        <li class="sidebar-item">
            <a href="/food-establishments/filter/0" class="sidebar-link">
                <i class="bi bi-hospital"></i>
                <span>Food Establishments</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a href="/sign-off/food-establishments" class="sidebar-link">
                <i class="bi bi-hospital"></i>
                <span>Approved Food Est List 2024</span>
            </a>
        </li>
        <li class="sidebar-item">
            <a href="/food-handlers-clinics/request/employees" class="sidebar-link">
                <i class="bi bi-check2-circle"></i>
                <span>Approve Onsite No. Edits</span>
            </a>
        </li>



        @if (in_array(auth()->user()->role_id, [1, 3, 4, 9]))
            <li class="sidebar-item">
                <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                    data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                    <i class="bi bi-coin"></i>
                    <span>Payments</span>
                </a>
                <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                    <li class="sidebar-item">
                        <a href="/payments/index/filter/0" class="sidebar-link">Processed Payments</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/payments/applications/filter/0" class="sidebar-link">Outstanding Applications</a>
                    </li>

                    <li class="sidebar-item">
                        <a href="/payments/create" class="sidebar-link">Create New Payment</a>
                    </li>

                </ul>
            </li>
        @endif

        {{-- Only The Cashiers and the Accountant can access these routes --}}
        @if (in_array(auth()->user()->role_id, [1, 4, 9]))
            <li class="sidebar-item">
                <a href="/payments/cancellations" class="sidebar-link">
                    <i class="bi bi-slash-circle"></i>
                    <span>Payment Cancel Requests</span>
                </a>
            </li>
        @endif


        <li class="sidebar-item">
            <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#downloads2" aria-expanded="false" aria-controls="reports">
                <i class="bi bi-file-earmark-medical"></i>
                <span>Test Results/Inspections</span>
            </a>
            <ul id="downloads2" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#download0" aria-expanded="false" aria-controls="reports">
                        <span>Food Handlers Permits</span>
                    </a>
                    <ul id="download0" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#download2">
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/permit/outstanding/filter/0" class="sidebar-link">Outstanding</a>
                        </li>
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/permit/filter/0" class="sidebar-link">Processed</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#download3" aria-expanded="false" aria-controls="reports">
                        <span>Food Establishments</span>
                    </a>
                    <ul id="download3" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#download2">
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/food-establishments/outstanding/filter/0"
                                class="sidebar-link">Outstanding</a>
                        </li>
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/food-establishments/filter/0" class="sidebar-link">Processed</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#download4" aria-expanded="false" aria-controls="reports">
                        <span>Swimming Pool</span>
                    </a>
                    <ul id="download4" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#download2">
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/swimming-pools/outstanding/filter/0"
                                class="sidebar-link">Outstanding</a>
                        </li>
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/swimming-pools/filter/0" class="sidebar-link">Processed</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#download5" aria-expanded="false" aria-controls="reports">
                        <span>Tourist Establishment</span>
                    </a>
                    <ul id="download5" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#download2">
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/tourist-establishments/outstanding/filter/0"
                                class="sidebar-link">Outstanding</a>
                        </li>
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/tourist-establishments/filter/0" class="sidebar-link">Processed</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                    <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#download6" aria-expanded="false" aria-controls="reports">
                        <span>Barber/Cosmet. etc.</span>
                    </a>
                    <ul id="download6" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#download2">
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/barber-cosmet/outstanding/filter/0"
                                class="sidebar-link">Outstanding</a>
                        </li>
                        <li class="sidebar-item mx-3">
                            <a href="/test-results/barber-cosmet/filter/0" class="sidebar-link">Processed</a>
                        </li>
                    </ul>
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
                    <a href="/health-interview/outstanding/filter/2/0" class="sidebar-link">Outstanding Health
                        Cert.</a>
                </li>
            </ul>
        </li>

        @if (in_array(auth()->user()->role_id, [1, 5, 10]))
        <li class="sidebar-item">
            <a href="{{ route('examsites.index') }}" class="sidebar-link">
                <i class="bi bi-slash-circle"></i>
                <span>Exam Sites</span></a>
        </li>
    @endif

        @if (in_array(auth()->user()->role_id, [1, 6]))
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
                        <a href="/downloads/food-establishments/filter/0" class="sidebar-link">Food Establishments</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/downloads/tourist-establishments/filter/0" class="sidebar-link">Tourist
                            Establishments</a>
                    </li>
                </ul>
            </li>
        @endif


        <li class="sidebar-item">
            <a href="" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                data-bs-target="#reports" aria-expanded="false" aria-controls="reports">
                <i class="bi bi-journal-check"></i>
                <span>Reports</span>
            </a>
            <ul id="reports" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                <li class="sidebar-item">
                    <a href="/reports/general-report" class="sidebar-link">General Report</a>
                </li>
                <li class="sidebar-item">
                    <a href="/report/summary-report" class="sidebar-link">Summary Report</a>
                </li>
                @if (in_array(auth()->user()->role_id, [1, 4, 9]))
                    <li class="sidebar-item">
                        <a href="/report/payment" class="sidebar-link">Check Off Report</a>
                    </li>
                @endif
                @if (in_array(auth()->user()->role_id, [1, 5, 10]))
                    <li class="sidebar-item">
                        <a href="/reports/app-by-category/create" class="sidebar-link">Applications By Category</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/reports/onsite-app/create" class="sidebar-link">Onsite Applications Report</a>
                    </li>
                @endif
                @if (in_array(auth()->user()->role_id, [1, 5, 10]))
                    <li class="sidebar-item">
                        <a href="/reports/sign-off/create" class="sidebar-link">Sign Off Report</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/reports/backlog-report" class="sidebar-link">Back Log Report</a>
                    </li>
                    <li class="sidebar-item">
                        <a href="/report/transactions" class="sidebar-link">Edit Transactions Report</a>
                    </li>
                @endif

                @if(in_array(auth()->user()->role_id,[1]))
                <li class="sidebar-item">
                    <a href="{{ route('report.generate.ai') }}" class="sidebar-link">AI Generated Report</a>
                </li>
                @endif

               

                <li class="sidebar-item">
                    <a href="/reports/printed-cards" class="sidebar-link">Printed Cards Report</a>
                </li>
                <li class="sidebar-item">
                    <a href="/reports/inspections" class="sidebar-link">Inspections Report</a>
                </li>
                <li class="sidebar-item">
                    <a href="{{ route('reports.category.zone') }}" class="sidebar-link">Est. Categories By Zone</a>
                </li>
            </ul>

        </li>


        @if (in_array(auth()->user()->role_id, [1]))
            <li class="sidebar-item">
                <a href="/settings/users" class="sidebar-link">
                    <i class="lni lni-cog"></i>
                    <span>User Settings</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="{{ route('examdates') }}" class="sidebar-link">
                    <i class="lni lni-cog"></i>
                    <span>Exam Dates</span>
                </a>
            </li>
            <li class="sidebar-item">
                <a href="/admin/settings" class="sidebar-link">
                    <i class="lni lni-cog"></i>
                    <span>Adminsitrative Settings</span>
                </a>
            </li>
        @else
            <li class="sidebar-item">
                <a href="#" class="sidebar-link">
                    <i class="lni lni-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        @endif

        <li class="sidebar-item">
            <a href="/training-manuals" class="sidebar-link">
                <i class="lni lni-cog"></i>
                <span>Training Manuals</span>
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
