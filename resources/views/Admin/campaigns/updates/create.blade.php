{{-- resources/views/admin/campaigns/updates/create.blade.php --}}
@extends('layouts.admin')



@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    {{-- Header Section --}}
    <div class="mb-8">
        <div class="flex items-center text-sm text-gray-800 dark:text-gray-300 mb-4">
            <a href="{{ route('admin.campaigns.index') }}" class="hover:text-primary-600">Campaigns</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.campaigns.show', $campaign->id) }}" class="hover:text-primary-600">{{ $campaign->title }}</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('admin.campaigns.updates.index', $campaign->id) }}" class="hover:text-primary-600 dark:text-gray-300">Updates</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900  dark:text-gray-300font-medium">Create Update</span>
        </div>

        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-300">Create Campaign Update</h1>
            <p class="text-gray-600 dark:text-gray-300 mt-1">Share progress and keep your donors engaged</p>
        </div>
    </div>

    {{-- Campaign Info Card --}}
    <div class="bg-gradient-to-r from-primary-50 to-primary-100 border border-primary-200 rounded-lg p-6 mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="w-10 h-10 text-primary-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4 flex-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $campaign->title }}</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">This update will be visible to {{ $campaign->donations()->where('status', 'successful')->distinct('user_id')->count('user_id') }} donor(s) who contributed to this campaign.</p>
                <div class="flex items-center space-x-4 text-sm text-gray-800 dark:text-gray-300">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        ${{ number_format($campaign->donations()->where('status', 'successful')->sum('amount'), 2) }} raised
                    </span>
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                        </svg>
                        {{ $campaign->updates()->whereNotNull('published_at')->count() }} updates
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Error Messages --}}
    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">There were errors with your submission:</h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Create Form --}}
    <form action="{{ route('admin.campaigns.updates.store', $campaign->id) }}" 
          method="POST" 
          enctype="multipart/form-data"
          x-data="{ 
              images: [], 
              notifyDonors: true,
              publishNow: true,
              previewImage(event) {
                  const files = event.target.files;
                  for (let i = 0; i < files.length; i++) {
                      const file = files[i];
                      const reader = new FileReader();
                      reader.onload = (e) => {
                          this.images.push({ 
                              url: e.target.result, 
                              name: file.name,
                              file: file
                          });
                      };
                      reader.readAsDataURL(file);
                  }
              },
              removeImage(index) {
                  this.images.splice(index, 1);
                  // Reset file input
                  document.getElementById('images').value = '';
              }
          }"
          
          class="space-y-6 ">
        @csrf

        {{-- Main Content Card --}}
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-900">
            <div class="p-6 space-y-6">
                {{-- Title Input --}}
                <div>
                    <label for="title" class="block text-sm font-semibold text-gray-900 mb-2 dark:text-gray-300">
                        Update Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title') }}"
                           required
                           placeholder="e.g., Milestone Reached: 50% of Goal Achieved!"
                           class="w-full px-4 py-3 border border-gray-300  dark:text-gray-300 dark:bg-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors @error('title') border-red-500 @enderror">
                    @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Choose a clear, engaging title that summarizes your update</p>
                </div>

                {{-- Content Editor --}}
              <div class="">
                <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Content <span class="text-red-500">*</span></label>
                <textarea id="markdown-editor" name="content" rows="8"
                    class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent @error('full_description') border-red-500 @enderror">{{ old('full_description') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

                {{-- Image Upload --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2  dark:text-gray-300">
                        Images (Optional)
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-primary-400 transition-colors">
                        <input type="file" 
                               name="images[]" 
                               id="images" 
                               multiple 
                               accept="image/*"
                               @change="previewImage"
                               class="hidden">
                        <label for="images" class="cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-600 mb-1 dark:text-gray-300">
                                <span class="font-medium text-primary-600 hover:text-primary-700 dark:text-gray-300">Click to upload</span>
                                or drag and drop
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-300">PNG, JPG, GIF up to 2MB each</p>
                        </label>
                    </div>
                    @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    {{-- Image Previews --}}
                    <div x-show="images.length > 0" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <template x-for="(image, index) in images" :key="index">
                            <div class="relative group">
                                <img :src="image.url" 
                                     :alt="image.name"
                                     class="w-full h-32 object-cover rounded-lg border border-gray-200">
                                <button type="button"
                                        @click="removeImage(index)"
                                        class="absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                                <div class="absolute bottom-2 left-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded truncate">
                                    <span x-text="image.name"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Publishing Options Card --}}
        <div class="bg-white border border-gray-200 dark:bg-gray-900  rounded-lg shadow-sm">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center dark:text-gray-300">
                    <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Publishing Options
                </h3>
                
                <div class="space-y-4">
                    {{-- Publish Now Option --}}
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
    <input type="hidden" name="publish_now" value="0">
    <input type="checkbox" 
           name="publish_now" 
           id="publish_now" 
           value="1"
           x-model="publishNow"
           {{ old('publish_now', true) ? 'checked' : '' }}
           class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
</div>
                        <div class="ml-3">
                            <label for="publish_now" class=" dark:bg-gray-900 font-medium text-gray-900 cursor-pointer dark:text-gray-300">
                                Publish immediately
                            </label>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Make this update visible right away. If unchecked, it will be saved as a draft.</p>
                        </div>
                    </div>

                    {{-- Notify Donors Option --}}
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
    <input type="hidden" name="notify_donors" value="0">
    <input type="checkbox" 
           name="notify_donors" 
           id="notify_donors" 
           value="1"
           x-model="notifyDonors"
           {{ old('notify_donors', true) ? 'checked' : '' }}
           class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
</div>
                        <div class="ml-3">
                            <label for="notify_donors" class="font-medium text-gray-900 cursor-pointer dark:text-gray-300">
                                Email all donors
                            </label>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                Send an email notification to all {{ $campaign->donations()->where('status', 'successful')->distinct('user_id')->count('user_id') }} donor(s) about this update
                                <span x-show="!publishNow" class="text-amber-600 font-medium">(will send when published)</span>
                            </p>
                        </div>
                    </div>

                    {{-- Email Preview Info --}}
                    <div x-show="notifyDonors && publishNow" 
                         class="bg-blue-50 border dark:bg-gray-900 border-blue-200 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-blue-800">
                                <p class="font-medium mb-1">Email will be sent immediately</p>
                                <p>Donors will receive an email with your update title and a link to read the full update on the campaign page.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-6 dark:bg-gray-900">
            <a href="{{ route('admin.campaigns.updates.index', $campaign->id) }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 dark:bg-gray-400 font-medium rounded-lg hover:bg-gray-100 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Cancel
            </a>
            <div class="flex space-x-3">
                <button type="submit" 
                        @click="publishNow = false"
                        class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-100 transition-colors dark:bg-gray-400">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                    </svg>
                    Save as Draft
                </button>
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors shadow-sm dark:bg-gray-400 dark:text-gray-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span x-text="publishNow ? 'Publish Update' : 'Save Draft'"></span>
                </button>
            </div>
        </div>
    </form>

    {{-- Tips Section --}}
    <div class="bg-gradient-to-br from-primary-50 to-blue-50 border border-primary-200 rounded-lg p-6 mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center dark:text-gray-300">
            <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            Tips for Great Campaign Updates
        </h3>
        <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
            <li class="flex items-start">
                <svg class="w-5 h-5 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Show progress:</strong> Share specific milestones, percentages reached, or goals achieved</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Tell stories:</strong> Share how donations are making a real impact with specific examples</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Add visuals:</strong> Include photos or videos to make your update more engaging</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Express gratitude:</strong> Thank your donors and acknowledge their contribution</span>
            </li>
            <li class="flex items-start">
                <svg class="w-5 h-5 text-primary-500 mr-2 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <span><strong>Be transparent:</strong> Share both successes and challenges to build trust</span>
            </li>
        </ul>
    </div>
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