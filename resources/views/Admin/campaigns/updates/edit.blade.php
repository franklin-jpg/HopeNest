@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Breadcrumb -->
    <nav class="mb-6">
        <ol class="flex items-center space-x-2 text-sm">
            <li><a href="{{ route('admin.campaigns.index') }}" class="text-blue-600 hover:underline">Campaigns</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li><a href="{{ route('admin.campaigns.show', $campaign) }}" class="text-blue-600 hover:underline">{{ Str::limit($campaign->title, 30) }}</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li><a href="{{ route('admin.campaigns.updates.index', $campaign) }}" class="text-blue-600 hover:underline">Updates</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-600 dark:text-gray-400">Edit</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Edit Campaign Update
                </h1>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $campaign->title }}
                </p>
            </div>
            <!-- Status Badge -->
            <div>
                @if($update->published_at)
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                        <i class="fas fa-check-circle"></i> Published
                    </span>
                @else
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-700">
                        <i class="fas fa-clock"></i> Draft
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Published Info (if applicable) -->
    @if($update->published_at)
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-green-600 dark:text-green-400 mt-1"></i>
                <div class="text-sm text-green-800 dark:text-green-300">
                    <p class="font-semibold">This update is already published</p>
                    <p class="text-xs mt-1">Published on {{ $update->published_at->format('F j, Y \a\t g:i A') }}</p>
                    @if($update->notify_donors)
                        <p class="text-xs mt-1"><i class="fas fa-envelope"></i> Donors were notified via email</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <form action="{{ route('admin.campaigns.updates.update', [$campaign, $update]) }}" 
          method="POST" 
          class="max-w-4xl">
        @csrf
        @method('PATCH')

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 space-y-6">
            <!-- Title -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Update Title <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $update->title) }}"
                       required
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500"
                       placeholder="Enter update title...">
                @error('title')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Content (Rich Text Editor) -->
               <div class="">
                <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Content <span class="text-red-500">*</span></label>
                <textarea id="markdown-editor" name="content" rows="8"
                    class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent @error('full_description') border-red-500 @enderror">{{ old('full_description') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Options -->
            <div class="space-y-3 border-t pt-4">
                <!-- Publish Now (only if not already published) -->
                @if(!$update->published_at)
                <div class="flex items-start">
                    <input type="checkbox" 
                           id="publish_now" 
                           name="publish_now" 
                           value="1"
                           {{ old('publish_now') ? 'checked' : '' }}
                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="publish_now" class="ml-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Publish Now</span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Make this update visible to donors</p>
                    </label>
                </div>
                @endif

                <!-- Notify Donors -->
                <div class="flex items-start">
                    <input type="checkbox" 
                           id="notify_donors" 
                           name="notify_donors" 
                           value="1"
                           {{ old('notify_donors', $update->notify_donors) ? 'checked' : '' }}
                           @if($update->published_at) disabled @endif
                           class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="notify_donors" class="ml-3">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                            Notify Donors
                            @if($update->published_at)
                                <span class="text-xs text-gray-500">(Cannot be changed after publishing)</span>
                            @endif
                        </span>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            @if($update->published_at && $update->notify_donors)
                                Donors were already notified when this update was published
                            @elseif($update->published_at)
                                Donors were not notified when this update was published
                            @else
                                Send email notifications to all campaign donors when published
                            @endif
                        </p>
                    </label>
                </div>
            </div>

            <!-- Update History -->
            <div class="bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <i class="fas fa-history"></i> Update History
                </h4>
                <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                    <p><strong>Created:</strong> {{ $update->created_at->format('F j, Y \a\t g:i A') }}</p>
                    <p><strong>Last Modified:</strong> {{ $update->updated_at->format('F j, Y \a\t g:i A') }}</p>
                    @if($update->published_at)
                        <p><strong>Published:</strong> {{ $update->published_at->format('F j, Y \a\t g:i A') }}</p>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3 pt-4 border-t">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                    <i class="fas fa-save"></i>
                    Update Changes
                </button>
                <a href="{{ route('admin.campaigns.updates.index', $campaign) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    Cancel
                </a>
                
                <!-- Delete Button -->
                <form action="{{ route('admin.campaigns.updates.destroy', [$campaign, $update]) }}" 
                      method="POST" 
                      class="ml-auto"
                      onsubmit="return confirm('Are you sure you want to delete this update? This action cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition flex items-center gap-2">
                        <i class="fas fa-trash-alt"></i>
                        Delete Update
                    </button>
                </form>
            </div>
        </div>
    </form>
</div>


@endsection
@push('scripts')

<!-- EasyMDE for Full Description & Thank You Message -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

         // Markdown Editors
    new EasyMDE({
        element: document.getElementById('markdown-editor'),
        spellChecker: false,
        placeholder: "Tell your campaign story in detail... (Markdown supported)",
        toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview", "side-by-side", "fullscreen", "|", "guide"]
    });

    new EasyMDE({
        element: document.getElementById('thank-you-editor'),
        spellChecker: false,
        placeholder: "Write a heartfelt thank you message to your donors...",
        toolbar: ["bold", "italic", "heading-3", "|", "quote", "link", "|", "preview"],
        status: false
    });
        });
</script>
@endpush