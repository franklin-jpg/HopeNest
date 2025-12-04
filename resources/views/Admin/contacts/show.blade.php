{{-- resources/views/admin/contacts/show.blade.php --}}
@extends('layouts.admin')

@section('title', 'Contact Message Details')

@section('content')
<div class="container mx-auto px-4 py-6">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Contact Message Details</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">
                View and manage this customer inquiry
            </p>
        </div>

        <div class="flex gap-3">
            <a href="mailto:{{ $contact->email }}?subject=Re: {{ ucfirst(str_replace('_', ' ', $contact->subject)) }}"
               class="inline-flex items-center px-5 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-reply mr-2"></i> Reply via Email
            </a>

            <form action="{{ route('admin.contacts.replied', $contact->id) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                @if($contact->status !== 'replied')
                <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-secondary transition-colors"
                        onclick="return confirm('Mark this message as replied?')">
                    <i class="fas fa-check-circle mr-2"></i> Mark as Replied
                </button>
                @else
                <span class="inline-flex items-center px-5 py-2.5 bg-gray-500 text-white rounded-lg opacity-75 cursor-not-allowed">
                    <i class="fas fa-check-circle mr-2"></i> Already Replied
                </span>
                @endif
            </form>
        </div>
    </div>

    <!-- Message Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden mb-6">
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user text-primary text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            {{ $contact->full_name }}
                            @if($contact->status == 'unread')
                                <span class="ml-2 text-xs text-blue-600 dark:text-blue-400">New</span>
                            @endif
                        </h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $contact->email }}</p>
                    </div>
                </div>

                <div class="text-right">
                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                        @if($contact->status == 'unread') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                        @elseif($contact->status == 'read') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                        @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                        {{ ucfirst($contact->status) }}
                    </span>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        {{ $contact->created_at->format('d M Y \a\t H:i') }}
                        <span class="text-xs">({{ $contact->created_at->diffForHumans() }})</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6 space-y-8">

            <!-- Subject Badge -->
            <div>
                <strong class="text-gray-700 dark:text-gray-300">Subject:</strong>
                <span class="ml-3 px-3 py-1 text-xs font-semibold rounded-full
                    {{ $contact->subject == 'general' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                    {{ $contact->subject == 'campaign' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                    {{ $contact->subject == 'donation' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : '' }}
                    {{ $contact->subject == 'technical' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' : '' }}
                    {{ $contact->subject == 'partnership' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300' : '' }}
                    {{ $contact->subject == 'feedback' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}">
                    {{ ucfirst(str_replace('_', ' ', $contact->subject)) }}
                </span>
            </div>

            <!-- Original Message -->
            <div>
                <strong class="text-gray-700 dark:text-gray-300 block mb-3">Original Message:</strong>
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                    <p class="text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $contact->message }}</p>
                </div>
            </div>

            <!-- Reply from Dashboard -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5">
                    <i class="fas fa-paper-plane text-primary mr-2"></i>
                    Reply to {{ $contact->first_name }}
                </h3>

                <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label for="reply_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Your Reply <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reply_message" id="reply_message" rows="8" required
                                  class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white resize-vertical"
                                  placeholder="Type your response here...">{{ old('reply_message') }}</textarea>
                        @error('reply_message')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-colors shadow-md">
                            <i class="fas fa-paper-plane mr-2"></i> Send Reply & Mark as Replied
                        </button>
                    </div>
                </form>
            </div>

            <!-- Admin Notes -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                <form action="{{ route('admin.contacts.notes', $contact->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <label for="admin_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-sticky-note mr-1"></i> Admin Notes (Internal only)
                    </label>
                    <textarea name="admin_notes" id="admin_notes" rows="5"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary dark:bg-gray-700 dark:text-white"
                              placeholder="Add internal notes, follow-up actions, etc...">{{ old('admin_notes', $contact->admin_notes) }}</textarea>

                    <div class="mt-3 flex justify-end">
                        <button type="submit"
                                class="px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-secondary transition-colors">
                            <i class="fas fa-save mr-2"></i> Save Notes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bottom Action Buttons -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.contacts.index') }}"
           class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
            <i class="fas fa-arrow-left mr-2"></i> Back to Messages
        </a>

        <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
              onsubmit="return confirm('Permanently delete this message? This action cannot be undone.')"
              class="inline">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center px-5 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                <i class="fas fa-trash mr-2"></i> Delete Message
            </button>
        </form>
    </div>
</div>
@endsection