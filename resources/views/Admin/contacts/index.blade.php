@extends('layouts.admin')

@section('title', 'Contact Messages')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Contact Messages</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Manage customer inquiries and support requests</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Total Messages</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <i class="fas fa-envelope text-blue-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Unread</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['unread'] }}</p>
                </div>
                <i class="fas fa-envelope-open text-yellow-500 text-3xl"></i>
            </div>
        </div>

        

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4 border-primary">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Replied</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $stats['replied'] }}</p>
                </div>
                <i class="fas fa-check-circle text-primary text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <input type="text" name="search" placeholder="Search messages..." 
                   value="{{ request('search') }}"
                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">

            <!-- Status Filter -->
            <select name="status" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                <option value="">All Status</option>
                <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
            </select>

            <!-- Subject Filter -->
            <select name="subject" 
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white">
                <option value="">All Subjects</option>
                <option value="general" {{ request('subject') == 'general' ? 'selected' : '' }}>General Inquiry</option>
                <option value="campaign" {{ request('subject') == 'campaign' ? 'selected' : '' }}>Campaign Support</option>
                <option value="donation" {{ request('subject') == 'donation' ? 'selected' : '' }}>Donation Question</option>
                <option value="technical" {{ request('subject') == 'technical' ? 'selected' : '' }}>Technical Issue</option>
                <option value="partnership" {{ request('subject') == 'partnership' ? 'selected' : '' }}>Partnership</option>
                <option value="feedback" {{ request('subject') == 'feedback' ? 'selected' : '' }}>Feedback</option>
            </select>

            <!-- Filter Button -->
            <div class="flex gap-2">
                <button type="submit" 
                        class="flex-1 bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('admin.contacts.index') }}" 
                   class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <i class="fas fa-redo"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Messages Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            From
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Subject
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Message Preview
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($contacts as $contact)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $contact->status == 'unread' ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $contact->full_name }}
                                        @if($contact->status == 'unread')
                                            <span class="ml-2 text-xs text-blue-600 dark:text-blue-400">●</span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $contact->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $contact->subject == 'general' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                {{ $contact->subject == 'campaign' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                {{ $contact->subject == 'donation' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                                {{ $contact->subject == 'technical' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}
                                {{ $contact->subject == 'partnership' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : '' }}
                                {{ $contact->subject == 'feedback' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}">
                                {{ ucfirst($contact->subject) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-xs">
                                {{ Str::limit($contact->message, 50) }}
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $contact->status == 'unread' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                {{ $contact->status == 'read' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                {{ $contact->status == 'replied' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}">
                                {{ ucfirst($contact->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $contact->created_at->diffForHumans() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="mailto:{{ $contact->email }}" 
                                   class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300"
                                   title="Reply">
                                    <i class="fas fa-reply"></i>
                                </a>
                                <form action="{{ route('admin.contacts.destroy', $contact->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this message?')"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-gray-400 text-5xl mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400">No contact messages found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($contacts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $contacts->links() }}
        </div>
        @endif
    </div>
</div>
@endsection