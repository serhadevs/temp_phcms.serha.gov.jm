<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHCMS - Onsite Verification - Powered By ID Pro</title>

    <!-- Tailwind CDN (utility classes only — used here to hand-build a shadcn/ui-style surface) -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Inter — the typeface shadcn/ui defaults to -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Lucide icons — same icon set shadcn/ui uses -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        :root {
            --background: 0 0% 100%;
            --foreground: 222.2 84% 4.9%;
            --muted: 210 40% 96.1%;
            --muted-foreground: 215.4 16.3% 46.9%;
            --border: 214.3 31.8% 91.4%;
            --ring: 222.2 84% 4.9%;
            --radius: 0.625rem;
        }

        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
            background-color: hsl(210 40% 98%);
            color: hsl(var(--foreground));
        }


        .filter-btn.active {
            background: hsl(var(--foreground));
            color: hsl(var(--background));
            border-color: hsl(var(--foreground));
        }


        .sc-card {
            background: hsl(var(--background));
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.03);
        }

        .sc-label {
            font-size: 0.75rem;
            font-weight: 500;
            color: hsl(var(--muted-foreground));
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .sc-value {
            font-size: 0.875rem;
            font-weight: 500;
            color: hsl(var(--foreground));
        }

        .sc-input {
            border: 1px solid hsl(var(--border));
            border-radius: var(--radius);
            background: hsl(var(--background));
            font-size: 0.875rem;
            transition: box-shadow 0.15s ease, border-color 0.15s ease;
        }

        .sc-input:focus {
            outline: none;
            border-color: hsl(var(--ring) / 0.4);
            box-shadow: 0 0 0 3px hsl(var(--ring) / 0.10);
        }

        .sc-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            font-weight: 500;
            border-radius: var(--radius);
            padding: 0.4rem 0.75rem;
            border: 1px solid hsl(var(--border));
            background: hsl(var(--background));
            color: hsl(var(--foreground));
            transition: background-color 0.15s ease;
        }

        .sc-btn:hover:not(:disabled) {
            background: hsl(var(--muted));
        }

        .sc-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .sc-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.15rem 0.6rem;
            border-radius: 9999px;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .sc-badge-success {
            background: #f0fdf4;
            color: #15803d;
            border-color: #bbf7d0;
        }

        .sc-badge-danger {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .sc-badge-warning {
            background: #fffbeb;
            color: #b45309;
            border-color: #fde68a;
        }

        .sc-badge-info {
            background: #eff6ff;
            color: #1d4ed8;
            border-color: #bfdbfe;
        }

        .sc-badge-neutral {
            background: hsl(var(--muted));
            color: hsl(var(--foreground));
            border-color: hsl(var(--border));
        }

        .sc-table th {
            font-size: 0.72rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: hsl(var(--muted-foreground));
            padding: 0.65rem 1rem;
            border-bottom: 1px solid hsl(var(--border));
            text-align: left;
        }

        .sc-table td {
            padding: 0.65rem 1rem;
            font-size: 0.875rem;
            border-bottom: 1px solid hsl(var(--border));
            vertical-align: middle;
        }

        .sc-table tbody tr:hover {
            background: hsl(var(--muted) / 0.5);
        }

        .sc-table tbody tr:last-child td {
            border-bottom: none;
        }

        .avatar {
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            object-fit: cover;
            border: 1px solid hsl(var(--border));
        }

        .avatar-fallback {
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            background: hsl(var(--muted));
            border: 1px solid hsl(var(--border));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            font-weight: 600;
            color: hsl(var(--muted-foreground));
        }

        .tab-trigger {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            white-space: nowrap;
            font-size: 0.8125rem;
            font-weight: 500;
            padding: 0.75rem 1.1rem;
            color: hsl(var(--muted-foreground));
            border-bottom: 2px solid transparent;
            margin-bottom: -1px;
            transition: color 0.15s ease, border-color 0.15s ease;
        }

        .tab-trigger:hover {
            color: hsl(var(--foreground));
        }

        .tab-trigger.active {
            color: hsl(var(--foreground));
            border-bottom-color: hsl(var(--foreground));
        }
    </style>
</head>

<body>

    <!-- Nav -->
    <nav class="border-b bg-white" style="border-color: hsl(var(--border));">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-14 flex items-center justify-between">
            <div class="flex items-center gap-2 font-semibold tracking-tight text-sm">
                <img src="{{ asset('images/serha_logo.png') }}" alt="SERHA Logo" width="25" height="25">
                Public Health Certificate Management System (PHCMS 2.0)
            </div>
            <a href="#" class="sc-btn" style="display: inline-flex; align-items: center; gap: 6px;">
                Permit Verification By IDPro
                <img src="{{ asset('images/idpro_logo.png') }}" alt="IDPro logo" style="height: 16px; width: auto;">
            </a>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        {{-- Flash error --}}
        @if (session('error'))
            <div class="sc-card border-red-200 bg-red-50 text-red-700 px-4 py-3 mb-5 flex items-start gap-2 text-sm">
                <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 shrink-0"></i>
                <div class="flex-1">{{ session('error') }}</div>
                <button type="button" class="text-red-500 hover:text-red-700"
                    onclick="this.closest('div.sc-card').remove()">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        {{-- Header --}}
        <div class="sc-card p-5 mb-5 flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="text-xl font-semibold tracking-tight">{{ $onsite->name ?? 'Unknown Establishment' }}</h1>
                <p class="text-sm mt-0.5" style="color: hsl(var(--muted-foreground));">
                    Onsite Verification &middot; Application #{{ $onsite->id ?? 'N/A' }}
                    @if ($earliestExpiry && $latestExpiry)
                        @if ($earliestExpiry->equalTo($latestExpiry))
                            &middot; Expires {{ $earliestExpiry->format('d/m/Y') }}
                        @else
                            &middot; Earliest Expiry: {{ $earliestExpiry->format('d/m/Y') }} &middot; Latest Expiry:
                            {{ $latestExpiry->format('d/m/Y') }}
                        @endif
                    @endif
                </p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">

                @if ($signedOffCount > 0)
                    <button type="button" id="download-permits-btn"
                        data-url="{{ route('onsite.permits.download', $onsite->id) }}" class="sc-btn"
                        style="background: hsl(var(--foreground)); color: hsl(var(--background)); border-color: hsl(var(--foreground));">
                        <i data-lucide="download" class="w-3.5 h-3.5"></i>
                        Download All Permits
                        @if ($signedOffCount < $onsite->permits->count())
                            <span class="sc-badge sc-badge-info" style="margin-left: 6px;">
                                {{ $signedOffCount }} of {{ $onsite->permits->count() }} ready
                            </span>
                        @endif
                    </button>
                @else
                    <span class="sc-badge sc-badge-warning">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i> Your application has not yet been signed off
                    </span>
                @endif

            </div>
        </div>

        {{-- Details --}}
        <div class="sc-card mb-5 overflow-hidden">

            <div class="flex border-b overflow-x-auto" style="border-color: hsl(var(--border));" role="tablist">
                <button type="button" class="tab-trigger active" data-tab="establishment" role="tab">
                    <i data-lucide="building-2" class="w-4 h-4"></i> Establishment Details
                </button>
                <button type="button" class="tab-trigger" data-tab="visit" role="tab">
                    <i data-lucide="calendar-check-2" class="w-4 h-4"></i> Visit Information
                </button>
                {{-- <button type="button" class="tab-trigger" data-tab="payment" role="tab">
                    <i data-lucide="credit-card" class="w-4 h-4"></i> Payment Details
                </button> --}}
            </div>

            {{-- Establishment Details --}}
            <div class="tab-panel p-5" data-tab-panel="establishment">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <div class="sc-label">Address</div>
                        <div class="sc-value">{{ $onsite->address ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="sc-label">Contact Person</div>
                        <div class="sc-value">{{ $onsite->contact_person ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="sc-label">Telephone</div>
                        <div class="sc-value">{{ $onsite->telephone ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="sc-label">Email Address</div>
                        <div class="sc-value">{{ $onsite->email_address ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="sc-label">Fax</div>
                        <div class="sc-value">{{ $onsite->fax_no ?? 'N/A' }}</div>
                    </div>
                    {{-- <div>
                        <div class="sc-label">No. of Employees</div>
                        <div class="sc-value">{{ $onsite->no_of_employees ?? 'N/A' }}</div>
                    </div> --}}
                </div>
            </div>

            {{-- Visit Information --}}
            <div class="tab-panel p-5 hidden" data-tab-panel="visit">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="sc-label">Application Date</div>
                        <div class="sc-value">
                            {{ \Carbon\Carbon::parse($onsite->application_date)->format('M d, Y') }}
                        </div>
                    </div>
                    <div>
                        <div class="sc-label">Proposed Visit Date</div>
                        <div class="sc-value">
                            {{ \Carbon\Carbon::parse($onsite->proposed_date)->format('M d, Y') }}
                        </div>
                    </div>
                    <div>
                        <div class="sc-label">Proposed Time</div>
                        <div class="sc-value">
                            {{ $onsite->proposed_time ? \Carbon\Carbon::parse($onsite->proposed_time)->format('h:i A') : 'N/A' }}
                        </div>
                    </div>

                </div>
            </div>

            {{-- Payment Details --}}
            {{-- <div class="tab-panel p-5 hidden" data-tab-panel="payment">
                @php
                    $amountDue = $onsite->due_payments ?? 0;
                    $hasWaiver = !is_null($onsite->waiver_establishment_id);
                @endphp
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="sc-label">Amount Due</div>
                        <div class="sc-value">
                            @if ($amountDue > 0)
                                <span class="sc-badge sc-badge-danger">
                                    JMD {{ number_format($amountDue, 2) }}
                                </span>
                            @else
                                <span class="sc-badge sc-badge-success">Fully Paid</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="sc-label">Payment Status</div>
                        <div class="sc-value">
                            @if ($amountDue > 0)
                                <span class="sc-badge sc-badge-warning">
                                    <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Outstanding
                                </span>
                            @else
                                <span class="sc-badge sc-badge-success">
                                    <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Paid
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="sc-label">Fee Waiver</div>
                        <div class="sc-value">
                            @if ($hasWaiver)
                                <span class="sc-badge sc-badge-info">Waiver Applied</span>
                            @else
                                <span class="sc-badge sc-badge-neutral">No Waiver</span>
                            @endif
                        </div>
                    </div>
                </div>

              
            </div> --}}

        </div>

        {{-- Permit holders table --}}
        <div class="sc-card p-5">
            <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
                <h2 class="text-sm font-semibold flex items-center gap-2">
                    <i data-lucide="users" class="w-4 h-4"></i>
                    Permit Holders
                    <span
                        class="sc-badge sc-badge-neutral">{{ $onsite->permits ? $onsite->permits->count() : 0 }}</span>
                </h2>

                <div class="flex items-center gap-3 flex-wrap w-full sm:w-auto">
                    {{-- Status filter --}}
                    <div class="flex items-center gap-1.5" id="statusFilter">
                        <button type="button" class="sc-btn filter-btn active" data-status="all">
                            All
                        </button>
                        <button type="button" class="sc-btn filter-btn" data-status="signed">
                            <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Signed Off
                        </button>
                        <button type="button" class="sc-btn filter-btn" data-status="pending">
                            <i data-lucide="clock" class="w-3.5 h-3.5"></i> Pending
                        </button>
                    </div>

                    <div class="relative w-full sm:w-72">
                        <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"
                            style="color: hsl(var(--muted-foreground));"></i>
                        <input type="text" id="tableSearch" class="sc-input w-full pl-9 pr-3 py-2"
                            placeholder="Search permit holders...">
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full sc-table" id="permitTable">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Permit No.</th>
                            <th>Name &amp; Address</th>
                            <th>TRN</th>
                            <th>Expiry Date</th>
                            <th>Approved</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($onsite->permits as $permit)
                            @php
                                $fullName = trim(
                                    $permit->firstname . ' ' . $permit->middlename . ' ' . $permit->lastname,
                                );
                                $initials = strtoupper(
                                    substr($permit->firstname ?? '', 0, 1) . substr($permit->lastname ?? '', 0, 1),
                                );
                                $hasPhoto =
                                    $permit->photo_upload &&
                                    \Illuminate\Support\Facades\Storage::disk('public')->exists($permit->photo_upload);
                                $statusKey = $permit->sign_off_status == 1 ? 'signed' : 'pending';
                            @endphp
                            <tr data-status="{{ $statusKey }}">
                                <td>
                                    @if ($hasPhoto)
                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($permit->photo_upload) }}"
                                            alt="{{ $fullName }}" class="avatar">
                                    @else
                                        <div class="avatar-fallback">{{ $initials ?: '?' }}</div>
                                    @endif
                                </td>
                                <td class="font-medium">{{ $permit->permit_no ?? 'N/A' }}</td>
                                <td>
                                    <div class="font-medium">{{ $fullName }}</div>
                                    <div class="text-xs" style="color: hsl(var(--muted-foreground));">
                                        {{ $permit->address ?? 'N/A' }}</div>
                                </td>
                                <td>{{ $permit->trn ?? 'N/A' }}</td>
                                <td>
                                    {{ $permit->signOffs?->expiry_date ? \Carbon\Carbon::parse($permit->signOffs->expiry_date)->format('d/m/Y') : 'N/A' }}
                                </td>


                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        @if ($permit->sign_off_status == 1)
                                            <span class="sc-badge"
                                                style="background: #E9F9EF; color: #1ea44c; border: 1px solid #1ea44c;">
                                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Signed Off
                                            </span>
                                        @else
                                            <span class="sc-badge"
                                                style="background: #FEF2F2; color: #d92d20; border: 1px solid #d92d20;">
                                                <i data-lucide="clock" class="w-3.5 h-3.5"></i> Pending Sign Off
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    @if ($permit->sign_off_status == 1)
                                        <a href="{{ route('verify.onsite.download', ['id' => $permit->id]) }}"
                                            class="sc-btn" title="Download Certificate">
                                            <i data-lucide="download" class="w-3.5 h-3.5"></i>
                                        </a>
                                    @else
                                        <span class="text-muted" title="No certificate available">
                                            <i data-lucide="file-x" class="w-3.5 h-3.5"></i>
                                            Not available
                                        </span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10"
                                    style="color: hsl(var(--muted-foreground));">
                                    No permit holders found for this establishment.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div id="noResults" class="hidden text-center py-10" style="color: hsl(var(--muted-foreground));">
                    <i data-lucide="search-x" class="w-6 h-6 mx-auto mb-2"></i>
                    <p class="text-sm">No permit holders match your search.</p>
                </div>
            </div>

            {{-- Pagination footer --}}
            <div id="paginationBar" class="flex items-center justify-between flex-wrap gap-3 pt-4 mt-1 border-t"
                style="border-color: hsl(var(--border));">
                <p class="text-xs" style="color: hsl(var(--muted-foreground));" id="paginationSummary">
                    Showing 0 of 0
                </p>
                <div class="flex items-center gap-1.5">
                    <button type="button" id="prevPage" class="sc-btn">
                        <i data-lucide="chevron-left" class="w-3.5 h-3.5"></i> Previous
                    </button>
                    <span class="text-xs px-2" style="color: hsl(var(--muted-foreground));" id="pageIndicator">Page 1
                        of 1</span>
                    <button type="button" id="nextPage" class="sc-btn">
                        Next <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        document.addEventListener('DOMContentLoaded', function() {
            // ---- Tabs (Establishment / Visit / Payment) ----
            const tabTriggers = document.querySelectorAll('.tab-trigger');
            const tabPanels = document.querySelectorAll('.tab-panel');

            tabTriggers.forEach(trigger => {
                trigger.addEventListener('click', function() {
                    const target = this.dataset.tab;

                    tabTriggers.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');

                    tabPanels.forEach(panel => {
                        panel.classList.toggle('hidden', panel.dataset.tabPanel !== target);
                    });
                });
            });

            // ---- Permit table search + status filter + pagination ----
            const ROWS_PER_PAGE = 10;

            const searchInput = document.getElementById('tableSearch');
            const tableBody = document.querySelector('#permitTable tbody');
            const allRows = Array.from(tableBody.querySelectorAll('tr')).filter(
                row => !row.querySelector('td[colspan]') // exclude the "no permits" empty-state row
            );
            const noResults = document.getElementById('noResults');
            const paginationBar = document.getElementById('paginationBar');
            const paginationSummary = document.getElementById('paginationSummary');
            const pageIndicator = document.getElementById('pageIndicator');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');
            const filterButtons = document.querySelectorAll('#statusFilter .filter-btn');

            let currentPage = 1;
            let activeStatus = 'all';

            if (allRows.length === 0) {
                searchInput.disabled = true;
                paginationBar.classList.add('hidden');
                return;
            }

            function getFilteredRows() {
                const term = searchInput.value.trim().toLowerCase();

                return allRows.filter(row => {
                    const matchesStatus = activeStatus === 'all' || row.dataset.status === activeStatus;
                    const matchesSearch = !term || row.textContent.toLowerCase().includes(term);
                    return matchesStatus && matchesSearch;
                });
            }

            function render() {
                const filtered = getFilteredRows();
                const totalPages = Math.max(1, Math.ceil(filtered.length / ROWS_PER_PAGE));
                currentPage = Math.min(currentPage, totalPages);

                allRows.forEach(row => {
                    row.style.display = 'none';
                });

                if (filtered.length === 0) {
                    noResults.classList.remove('hidden');
                } else {
                    noResults.classList.add('hidden');
                    const start = (currentPage - 1) * ROWS_PER_PAGE;
                    const pageRows = filtered.slice(start, start + ROWS_PER_PAGE);
                    pageRows.forEach(row => {
                        row.style.display = '';
                    });
                }

                const startIdx = filtered.length === 0 ? 0 : (currentPage - 1) * ROWS_PER_PAGE + 1;
                const endIdx = Math.min(currentPage * ROWS_PER_PAGE, filtered.length);
                paginationSummary.textContent = `Showing ${startIdx}-${endIdx} of ${filtered.length}`;
                pageIndicator.textContent = `Page ${currentPage} of ${totalPages}`;

                prevBtn.disabled = currentPage <= 1;
                nextBtn.disabled = currentPage >= totalPages;
            }

            searchInput.addEventListener('keyup', function() {
                currentPage = 1;
                render();
            });

            filterButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterButtons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    activeStatus = this.dataset.status;
                    currentPage = 1;
                    render();
                });
            });

            prevBtn.addEventListener('click', function() {
                if (currentPage > 1) {
                    currentPage--;
                    render();
                }
            });

            nextBtn.addEventListener('click', function() {
                currentPage++;
                render();
            });

            render();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('download-permits-btn');
            const modal = document.getElementById('permits-loading-modal');
            const icon = document.getElementById('permits-modal-icon');
            const title = document.getElementById('permits-modal-title');
            const message = document.getElementById('permits-modal-message');
            const closeBtn = document.getElementById('permits-modal-close');

            if (!btn) return;

            function showModal() {
                modal.classList.remove('hidden');
            }

            function hideModal() {
                modal.classList.add('hidden');
                // reset state for next click
                setLoadingState();
            }

            function setLoadingState() {
                icon.innerHTML = `
            <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
        `;
                title.textContent = 'Generating Permits';
                message.textContent = 'Please wait while we prepare your PDF. This may take a moment.';
                closeBtn.classList.add('hidden');
            }

            function setSuccessState() {
                icon.innerHTML = `
            <svg class="h-10 w-10 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
        `;
                title.textContent = 'Download Ready';
                message.textContent = 'Your permits PDF has started downloading.';
                closeBtn.classList.remove('hidden');
            }

            function setErrorState(errorMessage) {
                icon.innerHTML = `
            <svg class="h-10 w-10 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        `;
                title.textContent = 'Generation Failed';
                message.textContent = errorMessage || 'Something went wrong. Please try again.';
                closeBtn.classList.remove('hidden');
            }

            btn.addEventListener('click', async function() {
                const url = btn.dataset.url;

                setLoadingState();
                showModal();
                btn.disabled = true;

                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json, application/pdf',
                        },
                    });

                    const contentType = response.headers.get('Content-Type') || '';

                    if (!response.ok || contentType.includes('application/json')) {
                        const data = await response.json().catch(() => ({}));
                        setErrorState(data.error);
                        return;
                    }

                    // Success — extract filename from header if present
                    const disposition = response.headers.get('Content-Disposition') || '';
                    const match = disposition.match(/filename="?([^"]+)"?/);
                    const filename = match ? match[1] : 'permits.pdf';

                    const blob = await response.blob();
                    const blobUrl = window.URL.createObjectURL(blob);

                    const a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(blobUrl);

                    setSuccessState();

                } catch (err) {
                    console.error('Download error:', err);
                    setErrorState('A network error occurred. Please try again.');
                } finally {
                    btn.disabled = false;
                }
            });

            closeBtn.addEventListener('click', hideModal);

            // Optional: close modal when clicking outside it
            modal.addEventListener('click', function(e) {
                if (e.target === modal) hideModal();
            });
        });
    </script>
</body>

</html>
