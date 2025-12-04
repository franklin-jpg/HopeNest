{{-- resources/views/admin/donations/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-hand-holding-usd text-green-600"></i>
                All Donations
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and track all donations across campaigns</p>
        </div>

        <div class="flex items-center gap-3">
           
            <a href="{{ route('admin.donations.failed') }}"
               class="flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-exclamation-triangle"></i> Failed Donations
            </a>
        </div>
    </div>

  <!-- Statistics Cards – Compact & Beautiful -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">

    <!-- Total Donations -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Total</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                    {{ number_format($stats['total']) }}
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                <i class="fas fa-heart text-lg text-blue-600 dark:text-blue-400"></i>
            </div>
        </div>
    </div>

    <!-- Successful -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Successful</p>
                <p class="text-2xl font-bold text-green-600 mt-1">
                    {{ number_format($stats['successful']) }}
                </p>
            </div>
            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                <i class="fas fa-check text-lg text-green-600 dark:text-green-400"></i>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow p-5 border border-gray-100 dark:border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium">Pending</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">
                    {{ number_format($stats['pending']) }}
                </p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-lg text-yellow-600 dark:text-yellow-400"></i>
            </div>
        </div>
    </div>

    <!-- Total Raised – Hero Card (Intentionally allows slight overflow for impact) -->
    <div class="relative bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg shadow-lg p-5 text-white overflow-hidden col-span-2 md:col-span-1">
        <div class="relative z-10">
            <p class="text-xs font-semibold opacity-90">Total Raised</p>
            <p class="text-3xl md:text-4xl font-extrabold mt-2 tracking-tight leading-none">
                ₦{{ number_format($stats['total_amount'], 6) }}
            </p>
            <p class="text-xs mt-2 opacity-80">All successful donations</p>
        </div>
        
        <!-- Decorative icon background -->
        <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full"></div>
        <div class="absolute top-6 right-6">
            <i class="fas fa-coins text-4xl opacity-30"></i>
        </div>
    </div>

</div>

    <!-- Advanced Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 mb-6 border dark:border-gray-700">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-filter text-blue-600"></i>
                Advanced Filters
            </h2>
            <button type="button" onclick="toggleFilters()" class="text-blue-600 hover:text-blue-700 text-sm">
                <i class="fas fa-chevron-down" id="filterToggleIcon"></i>
            </button>
        </div>

        <form action="{{ route('admin.donations.index') }}" method="GET" id="filterForm">
            <div id="filterContent" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Name, email, receipt..."
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Campaign Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Campaign</label>
                    <select name="campaign_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">All Campaigns</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                                {{ $campaign->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

               

                <!-- Min Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Min Amount (₦)</label>
                    <input type="number" name="min_amount" value="{{ request('min_amount') }}" 
                           placeholder="0" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Max Amount -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Max Amount (₦)</label>
                    <input type="number" name="max_amount" value="{{ request('max_amount') }}" 
                           placeholder="Any" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex flex-wrap gap-2 mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('admin.donations.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-times mr-2"></i>Clear All
                </a>
                <button type="button" onclick="exportDonations('csv')" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition ml-auto">
                    <i class="fas fa-file-csv mr-2"></i>Export CSV
                </button>
                <button type="button" onclick="exportDonations('xlsx')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
            </div>
        </form>
    </div>

    <!-- Donations Table -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl border dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">
                            <input type="checkbox" id="selectAll" class="rounded" onclick="toggleSelectAll(this)">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Receipt</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Campaign</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Donor</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($donations as $donation)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <!-- Checkbox -->
                        <td class="px-6 py-4">
                            <input type="checkbox" class="donation-checkbox rounded" value="{{ $donation->id }}">
                        </td>

                        <!-- Receipt Number -->
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="text-sm font-mono font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $donation->receipt_number ?? 'N/A' }}
                                </span>
                                @if($donation->transaction_id)
                                    <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[120px]" title="{{ $donation->transaction_id }}">
                                        {{ Str::limit($donation->transaction_id, 12) }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        <!-- Campaign -->
                       <td class="px-6 py-4">
    @if($donation->campaign)
        <div class="flex items-start gap-2">
            <div class="flex-1 min-w-0">
                <a href="{{ route('admin.campaigns.show', $donation->campaign) }}"
                   class="text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 truncate block">
                    {{ Str::limit($donation->campaign->title, 30) }}
                </a>
                <span class="text-xs text-gray-500 dark:text-gray-400">
                    {{ $donation->campaign->campaignCategory?->name ?? 'Uncategorized' }}
                </span>
            </div>
        </div>
    @else
        <span class="text-sm text-gray-500 italic">Deleted Campaign</span>
    @endif
</td>

                        <!-- Donor Info -->
<!-- Donor Info -->
<td class="px-6 py-4 whitespace-nowrap">
    @if($donation->is_anonymous)
        <div class="flex items-center gap-2 text-gray-500 dark:text-gray-400">
            <i class="fas fa-user-secret"></i>
            <span class="text-sm font-medium">Anonymous Donor</span>
        </div>
    @else
        <div class="space-y-1">
            <!-- Name -->
            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                {{ $donation->donor_name }}
            </p>

            <!-- Email -->
            <a href="mailto:{{ $donation->donor_email }}"
               class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 hover:underline">
                {{ $donation->donor_email }}
            </a>

            <!-- Role Badges -->
            @if($donation->user)
                <div class="flex flex-wrap gap-1.5 mt-2">

                    <!-- Registered User Base Badge -->
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        <i class="fas fa-user-check mr-1"></i> Registered
                    </span>

                    <!-- Admin Check -->
                    @if($donation->user->role === 'admin' || $donation->user->role === 'super-admin')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                            <i class="fas fa-crown mr-1"></i> Admin
                        </span>
                    @endif

                    <!-- Volunteer Check -->
                    @if($donation->user->volunteer && $donation->user->volunteer->status === 'approved')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            <i class="fas fa-hands-helping mr-1"></i> Volunteer
                        </span>
                    @elseif($donation->user->volunteer && $donation->user->volunteer->status === 'pending')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                            <i class="fas fa-clock mr-1"></i> Volunteer (Pending)
                        </span>
                    @endif

                </div>
            @else
                <!-- Guest Donor -->
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 mt-1">
                    <i class="fas fa-user-alt-slash mr-1"></i> Guest Donor
                </span>
            @endif
        </div>
    @endif
</td>

                        <!-- Amount -->
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-bold text-gray-800 dark:text-gray-200">
                                    {{ $donation->getCurrencySymbol() }}{{ number_format($donation->amount, 2) }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $donation->currency }}</p>
                                @if($donation->processing_fee > 0)
                                    <p class="text-xs text-gray-600">
                                        Total: {{ $donation->getCurrencySymbol() }}{{ number_format($donation->total_amount, 2) }}
                                    </p>
                                @endif
                            </div>
                        </td>

                       

                        <!-- Status -->
                        <td class="px-6 py-4">
                            @if($donation->status === 'successful')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
                                    <i class="fas fa-check-circle mr-1"></i>Successful
                                </span>
                            @elseif($donation->status === 'pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @elseif($donation->status === 'failed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200">
                                    <i class="fas fa-times-circle mr-1"></i>Failed
                                </span>
                            @elseif($donation->status === 'refunded')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                    <i class="fas fa-undo mr-1"></i>Refunded
                                </span>
                            @endif
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            <div>
                                <p class="font-medium">{{ $donation->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $donation->created_at->format('g:i A') }}</p>
                                <p class="text-xs text-gray-400">{{ $donation->created_at->diffForHumans() }}</p>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.donations.show', $donation->id) }}"
                                   class="text-blue-600 hover:text-blue-700 dark:text-blue-400 p-2"
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($donation->status === 'successful')
                                    <button type="button" onclick="resendReceipt({{ $donation->id }})"
                                            class="text-green-600 hover:text-green-700 dark:text-green-400 p-2"
                                            title="Resend Receipt"
                                            {{ $donation->is_anonymous ? 'disabled' : '' }}>
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    
                                    <button type="button" onclick="openRefundModal({{ $donation->id }})"
                                            class="text-red-600 hover:text-red-700 dark:text-red-400 p-2"
                                            title="Refund">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                @endif

                              

                                @if($donation->message)
                                    <button type="button" onclick="showMessage('{{ addslashes($donation->message) }}')"
                                            class="text-purple-600 hover:text-purple-700 dark:text-purple-400 p-2"
                                            title="View Message">
                                        <i class="fas fa-comment-alt"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-hand-holding-usd text-5xl mb-3 text-gray-400"></i>
                            <p class="text-lg">No donations found</p>
                            <p class="text-sm text-gray-500 mt-1">Donations will appear here as supporters contribute to campaigns.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($donations->hasPages())
        <div class="border-t dark:border-gray-700 p-4">
            {{ $donations->links() }}
        </div>
        @endif
    </div>

    <!-- Bulk Actions Bar (hidden by default) -->
    <div id="bulkActionsBar" class="hidden fixed bottom-0 left-0 right-0 bg-blue-600 text-white p-4 shadow-lg z-50">
        <div class="container mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span id="selectedCount">0</span> donations selected
            </div>
            <div class="flex items-center gap-2">
                <button onclick="bulkExport()" class="bg-white text-blue-600 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-download mr-2"></i>Export Selected
                </button>
                <button onclick="clearSelection()" class="bg-blue-700 text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Refund Donation</h3>
            <button onclick="closeRefundModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="refundForm" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <p class="text-sm text-red-800 dark:text-red-200">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        This action cannot be undone. The donation will be refunded and the campaign amount will be updated.
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Refund Reason <span class="text-red-600">*</span>
                    </label>
                    <textarea name="refund_reason" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500"
                              placeholder="Please provide a reason for this refund..."></textarea>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 p-6 border-t dark:border-gray-700">
                <button type="button" onclick="closeRefundModal()" 
                        class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    <i class="fas fa-undo mr-2"></i>Process Refund
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let filtersExpanded = true;

function toggleFilters() {
    filtersExpanded = !filtersExpanded;
    const content = document.getElementById('filterContent');
    const icon = document.getElementById('filterToggleIcon');
    
    if (filtersExpanded) {
        content.style.display = 'grid';
        icon.classList.remove('fa-chevron-right');
        icon.classList.add('fa-chevron-down');
    } else {
        content.style.display = 'none';
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-right');
    }
}

function exportDonations(format) {
    const params = new URLSearchParams(window.location.search);
    params.set('export', format);
    window.location.href = `{{ route('admin.donations.index') }}?${params.toString()}`;
}

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.donation-checkbox');
    checkboxes.forEach(cb => cb.checked = checkbox.checked);
    updateBulkActionsBar();
}

function updateBulkActionsBar() {
    const checkboxes = document.querySelectorAll('.donation-checkbox:checked');
    const bar = document.getElementById('bulkActionsBar');
    const count = document.getElementById('selectedCount');
    
    if (checkboxes.length > 0) {
        bar.classList.remove('hidden');
        count.textContent = checkboxes.length;
    } else {
        bar.classList.add('hidden');
    }
}

function clearSelection() {
    document.querySelectorAll('.donation-checkbox').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkActionsBar();
}

function bulkExport() {
    const selected = Array.from(document.querySelectorAll('.donation-checkbox:checked'))
        .map(cb => cb.value);
    
    if (selected.length === 0) {
        alert('Please select donations to export');
        return;
    }
    
    // Create form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.donations.index") }}';
    
    const csrfInput = document.createElement('input');
    csrfInput.type = 'hidden';
    csrfInput.name = '_token';
    csrfInput.value = '{{ csrf_token() }}';
    form.appendChild(csrfInput);
    
    selected.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'donation_ids[]';
        input.value = id;
        form.appendChild(input);
    });
    
    document.body.appendChild(form);
    form.submit();
}

function openRefundModal(donationId) {
    const modal = document.getElementById('refundModal');
    const form = document.getElementById('refundForm');
    form.action = `/admin/donations/${donationId}/refund`;
    modal.classList.remove('hidden');
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
    document.getElementById('refundForm').reset();
}

function resendReceipt(donationId) {
    if (!confirm('Are you sure you want to resend the receipt to this donor?')) {
        return;
    }
    
    fetch(`/admin/donations/${donationId}/resend-receipt`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Receipt resent successfully!');
        } else {
            alert('Failed to resend receipt: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while resending the receipt');
    });
}

function sendRetryLink(donationId) {
    if (!confirm('Send a retry payment link to this donor?')) {
        return;
    }
    
    fetch(`/admin/donations/${donationId}/send-retry-link`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Retry link sent successfully!');
        } else {
            alert('Failed to send retry link: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while sending the retry link');
    });
}

function showMessage(message) {
    alert('Donor Message:\n\n' + message);
}

// Add event listeners to checkboxes
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.donation-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActionsBar);
    });
    
    // Close modals on outside click
    document.getElementById('refundModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeRefundModal();
        }
    });
});
</script>
@endpush
@endsection