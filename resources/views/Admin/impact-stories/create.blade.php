@extends('layouts.admin')

@section('title', isset($impactStory) ? 'Edit Impact Story' : 'Create Impact Story')



@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ isset($impactStory) ? 'Edit Impact Story' : 'Create Impact Story' }}
                    </h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {{ isset($impactStory) ? 'Update story details' : 'Create a new impact story to showcase your campaign results' }}
                    </p>
                </div>
                <a href="{{ route('admin.impact-stories.index') }}" 
                   class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Back to Stories
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-400 dark:text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            There were errors with your submission
                        </h3>
                        <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ isset($impactStory) ? route('admin.impact-stories.update', $impactStory) : route('admin.impact-stories.store') }}" 
              method="POST" 
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @if(isset($impactStory))
                @method('PATCH')
            @endif

            <!-- Main Content Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Story Details</h2>
                
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="title" 
                           id="title" 
                           value="{{ old('title', $impactStory->title ?? '') }}"
                           required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Excerpt -->
                <div class="mb-6">
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Short Excerpt <span class="text-red-500">*</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">(Max 200 characters)</span>
                    </label>
                    <textarea name="excerpt" 
                              id="excerpt" 
                              rows="3"
                              maxlength="200"
                              required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $impactStory->excerpt ?? '') }}</textarea>
                    <div class="flex justify-between mt-1">
                        @error('excerpt')
                            <p class="text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-500 dark:text-gray-400">Brief description for preview cards</p>
                        @enderror
                        <span id="excerpt-count" class="text-xs text-gray-500 dark:text-gray-400">0/200</span>
                    </div>
                </div>

                <!-- Content Editor -->
                <div>
                <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Content <span class="text-red-500">*</span></label>
                <textarea id="markdown-editor" name="content" rows="8"
                    class="w-full border rounded-lg px-4 py-3 dark:bg-gray-900 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                @error('content')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

                <!-- Campaign Link -->
                <div class="mb-6">
                    <label for="campaign_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Link to Campaign <span class="text-xs text-gray-500 dark:text-gray-400">(Optional)</span>
                    </label>
                    <select name="campaign_id" 
                            id="campaign_id"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        <option value="">-- No Campaign --</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" 
                                    {{ old('campaign_id', $impactStory->campaign_id ?? '') == $campaign->id ? 'selected' : '' }}>
                                {{ $campaign->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Media Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Featured Image</h2>
                
                <div class="mb-6">
                    <label for="featured_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Upload Image <span class="text-red-500">{{ isset($impactStory) ? '' : '*' }}</span>
                    </label>
                    
                    @if(isset($impactStory) && $impactStory->featured_image)
                        <div class="mb-4 relative">
                            <img src="{{ asset('storage/' . $impactStory->featured_image) }}" 
                                 alt="Current featured image"
                                 class="w-full h-64 object-cover rounded-lg">
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Current image - upload a new one to replace</p>
                        </div>
                    @endif
                    
                    <input type="file" 
                           name="featured_image" 
                           id="featured_image"
                           accept="image/*"
                           {{ isset($impactStory) ? '' : 'required' }}
                           class="block w-full text-sm text-gray-500 dark:text-gray-400
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-lg file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  dark:file:bg-indigo-900 dark:file:text-indigo-300
                                  hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800
                                  @error('featured_image') border-red-500 @enderror">
                    @error('featured_image')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @else
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Recommended size: 1200x630px (JPG, PNG, WebP, max 2MB)</p>
                    @enderror
                </div>

                <!-- Image Preview -->
                <div id="image-preview" class="hidden">
                    <img id="preview-img" class="w-full h-64 object-cover rounded-lg" alt="Preview">
                </div>
            </div>

            <!-- Beneficiary Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Beneficiary Information</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="beneficiary_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Beneficiary Name
                        </label>
                        <input type="text" 
                               name="beneficiary_name" 
                               id="beneficiary_name"
                               value="{{ old('beneficiary_name', $impactStory->beneficiary_name ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                    </div>

                    <div>
                        <label for="beneficiary_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Location
                        </label>
                        <input type="text" 
                               name="beneficiary_location" 
                               id="beneficiary_location"
                               value="{{ old('beneficiary_location', $impactStory->beneficiary_location ?? '') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                    </div>

                    <div>
                        <label for="impact_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Impact Date
                        </label>
                        <input type="date" 
                               name="impact_date" 
                               id="impact_date"
                               value="{{ old('impact_date', isset($impactStory) && $impactStory->impact_date ? $impactStory->impact_date->format('Y-m-d') : '') }}"
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                    </div>
                </div>
            </div>

            <!-- Impact Metrics Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Impact Metrics</h2>
                    <button type="button" 
                            id="add-metric" 
                            class="px-3 py-1 text-sm bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:hover:bg-indigo-800 text-indigo-700 dark:text-indigo-300 rounded-lg transition">
                        + Add Metric
                    </button>
                </div>
                
                <div id="metrics-container" class="space-y-3">
                    @if(isset($impactStory) && $impactStory->metrics)
                        @foreach($impactStory->metrics as $index => $metric)
                            <div class="metric-row flex gap-3">
                                <input type="text" 
                                       name="metrics[{{ $index }}][label]" 
                                       value="{{ $metric['label'] }}"
                                       placeholder="e.g., Families Helped"
                                       class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                <input type="text" 
                                       name="metrics[{{ $index }}][value]" 
                                       value="{{ $metric['value'] }}"
                                       placeholder="e.g., 150"
                                       class="w-32 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                                <button type="button" 
                                        class="remove-metric px-3 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-300 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    @endif
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Add quantifiable impact metrics (e.g., "500 Meals Served", "10 Families Housed")</p>
            </div>

            <!-- Status & Settings Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Publication Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" 
                                id="status"
                                required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                            <option value="draft" {{ old('status', $impactStory->status ?? 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $impactStory->status ?? '') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="archived" {{ old('status', $impactStory->status ?? '') == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" 
                                   name="is_featured" 
                                   value="1"
                                   {{ old('is_featured', $impactStory->is_featured ?? false) ? 'checked' : '' }}
                                   class="w-5 h-5 text-indigo-600 border-gray-300 dark:border-gray-600 rounded focus:ring-indigo-500 dark:focus:ring-indigo-400 dark:bg-gray-700">
                            <span class="ml-3">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured Story</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Display prominently on homepage</p>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-4">
                <a href="{{ route('admin.impact-stories.index') }}" 
                   class="px-6 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition">
                    {{ isset($impactStory) ? 'Update Story' : 'Create Story' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
<!-- EasyMDE for Full Description & Thank You Message -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.js"></script>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
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


    // Excerpt character counter
    const excerptTextarea = document.getElementById('excerpt');
    const excerptCount = document.getElementById('excerpt-count');
    
    function updateExcerptCount() {
        const count = excerptTextarea.value.length;
        excerptCount.textContent = `${count}/200`;
    }
    
    excerptTextarea.addEventListener('input', updateExcerptCount);
    updateExcerptCount();

    // Image preview
    const imageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Metrics management
    let metricIndex = {{ isset($impactStory) && $impactStory->metrics ? count($impactStory->metrics) : 0 }};
    
    document.getElementById('add-metric').addEventListener('click', function() {
        const container = document.getElementById('metrics-container');
        const metricRow = document.createElement('div');
        metricRow.className = 'metric-row flex gap-3';
        metricRow.innerHTML = `
            <input type="text" 
                   name="metrics[${metricIndex}][label]" 
                   placeholder="e.g., Families Helped"
                   class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
            <input type="text" 
                   name="metrics[${metricIndex}][value]" 
                   placeholder="e.g., 150"
                   class="w-32 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
            <button type="button" 
                    class="remove-metric px-3 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-300 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        container.appendChild(metricRow);
        metricIndex++;
    });

    // Remove metric
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-metric')) {
            e.target.closest('.metric-row').remove();
        }
    });
});
</script>
@endpush