{{-- resources/views/admin/campaigns/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<main class="max-w-5xl mx-auto space-y-6 py-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Create Campaign</h1>

        <a href="{{ route('admin.campaigns.index') }}"
           class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition text-sm">
            <i class="fas fa-arrow-left"></i>
            Go Back
        </a>
    </div>

    <form action="{{ route('admin.campaigns.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <strong>Please fix the following errors:</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

        <!-- Basic Info -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Basic Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Title -->
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category - Now matches Title style -->
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Category <span class="text-red-500">*</span></label>
                    <select name="campaign_category_id" required
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent @error('campaign_category_id') border-red-500 @enderror">
                        <option value="" disabled {{ old('campaign_category_id') ? '' : 'selected' }}>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('campaign_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('campaign_category_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                
                <!-- Location -->
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Location <span class="text-red-500">*</span></label>
                    <input type="text" name="location" value="{{ old('location') }}" required placeholder="e.g. Lagos, Nigeria"
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent @error('location') border-red-500 @enderror">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Short Description -->
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">
                        Short Description <span class="text-red-500">*</span>
                        <span class="text-sm text-gray-500 font-normal">(150 characters max)</span>
                    </label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}" maxlength="150"
                        placeholder="A brief summary of your campaign..."
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent @error('short_description') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1" id="char-count">0 / 150 characters</p>
                    @error('short_description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div>
    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">
        Minimum Donation (₦) <span class="text-red-500">*</span>
    </label>
    <input type="number" step="0.01" name="minimum_donation" 
           value="{{ old('minimum_donation') }}" required
           class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary @error('minimum_donation') border-red-500 @enderror">
    @error('minimum_donation')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
            </div>

            <!-- Full Description -->
            <div>
                <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Full Description (Markdown Supported) <span class="text-red-500">*</span></label>
                <textarea id="markdown-editor" name="full_description" rows="8"
                    class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent @error('full_description') border-red-500 @enderror">{{ old('full_description') }}</textarea>
                @error('full_description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Custom Thank You Message - NEW -->
            <div>
                <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">
                    Custom Thank You Message <span class="text-gray-500 text-sm">(Optional - Shown to donors after donation)</span>
                </label>
                <textarea id="thank-you-editor" name="custom_thank_you" rows="5"
                    placeholder="e.g. Thank you so much for your kindness and support! Your donation means the world to us..."
                    class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent">{{ old('custom_thank_you') }}</textarea>
                <p class="text-xs text-gray-500 mt-2">Markdown supported • Personalize your gratitude</p>
            </div>
        </div>

        <!-- Funding Details -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Funding Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Goal Amount (₦) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="goal_amount" value="{{ old('goal_amount') }}" required
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary @error('goal_amount') border-red-500 @enderror">
                    @error('goal_amount') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Start Date <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}" required
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary @error('start_date') border-red-500 @enderror">
                    @error('start_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">End Date <span class="text-gray-500 text-sm">(Optional)</span></label>
                    <input type="datetime-local" name="end_date" value="{{ old('end_date') }}"
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary">
                </div>
            </div>
        </div>

        <!-- Media -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Media</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Featured Image <span class="text-red-500">*</span></label>
                    <input type="file" name="featured_image" accept="image/*" required
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white @error('featured_image') border-red-500 @enderror">
                    @error('featured_image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Video URL <span class="text-gray-500 text-sm">(Optional)</span></label>
                    <input type="url" name="video_url" value="{{ old('video_url') }}" placeholder="https://youtube.com/watch?v=..."
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Gallery Images <span class="text-gray-500 text-sm">(Optional • Multiple)</span></label>
                <div class="flex flex-col md:flex-row items-start gap-4">
                    <input type="file" name="gallery_images[]" multiple accept="image/*" id="galleryInput"
                        class="md:w-1/2 border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white @error('gallery_images') border-red-500 @enderror">
                    <div id="galleryPreview" class="grid grid-cols-3 gap-3 md:w-1/2"></div>
                </div>
                @error('gallery_images') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Additional Settings -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow space-y-6">
            <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Additional Settings</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">Status</label>
                    <select name="status"
                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary @error('status') border-red-500 @enderror">
                        <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="space-y-4">
                    <label class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="is_featured" value="1" class="w-5 h-5 rounded text-primary" {{ old('is_featured') ? 'checked' : '' }}>
                        <span class="font-medium">Mark as Featured</span>
                    </label>
                    <label class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="is_urgent" value="1" class="w-5 h-5 rounded text-primary" {{ old('is_urgent') ? 'checked' : '' }}>
                        <span class="font-medium">Mark as Urgent</span>
                    </label>
                    <label class="flex items-center gap-3 text-gray-700 dark:text-gray-300">
                        <input type="checkbox" name="recurring_donations" value="1" class="w-5 h-5 rounded text-primary" {{ old('recurring_donations') ? 'checked' : '' }}>
                        <span class="font-medium">Enable Recurring Donations</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end">
            <button type="submit" class="bg-primary hover:bg-primary/90 text-white font-bold px-8 py-4 rounded-lg text-lg transition shadow-lg">
                Create Campaign
            </button>
        </div>
    </form>
</main>
@endsection

@push('scripts')
<!-- EasyMDE for Full Description & Thank You Message -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Character Counter
    const shortDescInput = document.querySelector('input[name="short_description"]');
    const charCount = document.getElementById('char-count');
    if (shortDescInput && charCount) {
        const updateCount = () => {
            const len = shortDescInput.value.length;
            charCount.textContent = `${len} / 150 characters`;
            charCount.classList.toggle('text-amber-600', len > 140);
        };
        updateCount();
        shortDescInput.addEventListener('input', updateCount);
    }

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

    // Gallery Preview (unchanged - still perfect)
    const galleryInput = document.getElementById('galleryInput');
    const galleryPreview = document.getElementById('galleryPreview');
    if (!galleryInput || !galleryPreview) return;

    let selectedFiles = [];

    function updatePreview() {
        galleryPreview.innerHTML = '';
        selectedFiles.forEach((file, idx) => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = e => {
                const wrapper = document.createElement('div');
                wrapper.className = 'relative group';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.className = 'w-32 h-32 object-cover rounded-lg border-2 border-gray-300 shadow';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.innerHTML = '×';
                removeBtn.className = 'absolute top-2 right-2 w-8 h-8 bg-red-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition';
                removeBtn.onclick = () => {
                    selectedFiles.splice(idx, 1);
                    updateInputFiles();
                    updatePreview();
                };

                wrapper.appendChild(img);
                wrapper.appendChild(removeBtn);
                galleryPreview.appendChild(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }

    function updateInputFiles() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        galleryInput.files = dt.files;
    }

    galleryInput.addEventListener('change', () => {
        selectedFiles = selectedFiles.concat(Array.from(galleryInput.files));
        updateInputFiles();
        updatePreview();
    });

    // Drag & Drop styling
    ['dragover', 'dragenter'].forEach(evt => galleryInput.addEventListener(evt, e => {
        e.preventDefault();
        galleryInput.classList.add('border-primary', 'bg-primary/5');
    }));
    ['dragleave', 'dragend', 'drop'].forEach(evt => galleryInput.addEventListener(evt, e => {
        e.preventDefault();
        galleryInput.classList.remove('border-primary', 'bg-primary/5');
    }));
    galleryInput.addEventListener('drop', e => {
        const dropped = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
        selectedFiles = selectedFiles.concat(dropped);
        updateInputFiles();
        updatePreview();
    });
});
</script>
@endpush