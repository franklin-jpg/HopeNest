{{-- resources/views/admin/notifications/index.blade.php --}}
@extends('layouts.admin')

@section('title', 'Notifications')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Notifications</h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    You have <span class="font-semibold text-primary">{{ $unreadCount }}</span> unread notifications
                </p>
            </div>
            
            <div class="flex gap-2 w-full md:w-auto">
                @if($unreadCount > 0)
                    <button onclick="markAllAsRead()" 
                            class="flex-1 md:flex-none px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors text-sm font-medium">
                        <i class="fas fa-check-double mr-2"></i>Mark All Read
                    </button>
                @endif
                
                @if($notifications->total() > 0)
                    <form action="{{ route('admin.notifications.destroy-all') }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete all notifications?')"
                          class="flex-1 md:flex-none">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                            <i class="fas fa-trash mr-2"></i>Clear All
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-400 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <!-- Notifications List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            @forelse($notifications as $notification)
                @php
                    $data = $notification->data;
                    $isUnread = $notification->read_at === null;
                    
                    // Determine icon and color based on type
                    $iconClass = 'fa-bell';
                    $iconColor = 'text-blue-500';
                    $bgColor = 'bg-blue-100 dark:bg-blue-900/30';
                    
                    switch($data['type'] ?? '') {
                        case 'user_registered':
                            $iconClass = 'fa-user-plus';
                            $iconColor = 'text-green-500';
                            $bgColor = 'bg-green-100 dark:bg-green-900/30';
                            break;
                        case 'donation_received':
                            $iconClass = 'fa-hand-holding-heart';
                            $iconColor = 'text-pink-500';
                            $bgColor = 'bg-pink-100 dark:bg-pink-900/30';
                            break;
                        case 'contact_message':
                            $iconClass = 'fa-envelope';
                            $iconColor = 'text-yellow-500';
                            $bgColor = 'bg-yellow-100 dark:bg-yellow-900/30';
                            break;
                        case 'volunteer_application':
                            $iconClass = 'fa-hands-helping';
                            $iconColor = 'text-purple-500';
                            $bgColor = 'bg-purple-100 dark:bg-purple-900/30';
                            break;
                    }
                @endphp
                
                <div class="border-b border-gray-200 dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors {{ $isUnread ? 'bg-blue-50/50 dark:bg-blue-900/10' : '' }}">
                    <div class="p-4 sm:p-6 flex items-start gap-4">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full {{ $bgColor }} flex items-center justify-center">
                                <i class="fas {{ $iconClass }} {{ $iconColor }} text-xl"></i>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-800 dark:text-white {{ $isUnread ? 'font-semibold' : '' }}">
                                        {{ $data['message'] ?? 'New notification' }}
                                    </p>
                                    
                                    <!-- Additional Details Based on Type -->
                                    @if($data['type'] === 'donation_received')
                                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                            <p><span class="font-medium">Amount:</span> ₦{{ number_format($data['amount'] ?? 0, 2) }}</p>
                                            <p><span class="font-medium">Campaign:</span> {{ $data['campaign_title'] ?? 'N/A' }}</p>
                                            <p><span class="font-medium">Donor:</span> {{ $data['donor_name'] ?? 'N/A' }}</p>
                                        </div>
                                    @elseif($data['type'] === 'user_registered')
                                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                            <p><span class="font-medium">Email:</span> {{ $data['user_email'] ?? 'N/A' }}</p>
                                            <p><span class="font-medium">Role:</span> <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 rounded">{{ ucfirst($data['user_role'] ?? 'user') }}</span></p>
                                        </div>
                                    @elseif($data['type'] === 'contact_message')
                                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                            <p><span class="font-medium">From:</span> {{ $data['sender_name'] ?? 'N/A' }}</p>
                                            <p><span class="font-medium">Email:</span> {{ $data['sender_email'] ?? 'N/A' }}</p>
                                            <p><span class="font-medium">Subject:</span> <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 rounded">{{ ucfirst($data['subject'] ?? 'general') }}</span></p>
                                        </div>
                                    @elseif($data['type'] === 'volunteer_application')
                                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                            <p><span class="font-medium">Name:</span> {{ $data['volunteer_name'] ?? 'N/A' }}</p>
                                            <p><span class="font-medium">Email:</span> {{ $data['volunteer_email'] ?? 'N/A' }}</p>
                                            <p><span class="font-medium">Status:</span> <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 rounded">{{ ucfirst($data['status'] ?? 'pending') }}</span></p>
                                        </div>
                                    @endif
                                    
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">
                                        <i class="far fa-clock mr-1"></i>{{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                                
                                @if($isUnread)
                                    <span class="w-2 h-2 bg-primary rounded-full flex-shrink-0 mt-1" title="Unread"></span>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex flex-wrap gap-3 mt-4">
                                @if(isset($data['url']))
                                    <a href="{{ $data['url'] }}" 
                                       class="inline-flex items-center text-xs text-white bg-primary hover:bg-primary-dark px-3 py-1.5 rounded-md font-medium transition-colors">
                                        View Details <i class="fas fa-arrow-right ml-1.5"></i>
                                    </a>
                                @endif
                                
                                @if($isUnread)
                                    <button onclick="markAsRead('{{ $notification->id }}')" 
                                            class="inline-flex items-center text-xs text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 px-3 py-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <i class="fas fa-check mr-1.5"></i>Mark as read
                                    </button>
                                @endif
                                
                                <button onclick="deleteNotification('{{ $notification->id }}')" 
                                        class="inline-flex items-center text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 border border-red-300 dark:border-red-600 px-3 py-1.5 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i class="fas fa-trash mr-1.5"></i>Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                        <i class="fas fa-bell-slash text-4xl text-gray-300 dark:text-gray-600"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white mb-1">No notifications yet</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">When you receive notifications, they'll appear here</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/admin/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark notification as read');
    });
}

function markAllAsRead() {
    fetch('/admin/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to mark all notifications as read');
    });
}

function deleteNotification(notificationId) {
    if (!confirm('Are you sure you want to delete this notification?')) return;
    
    fetch(`/admin/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete notification');
    });
}
</script>
@endpush
@endsection