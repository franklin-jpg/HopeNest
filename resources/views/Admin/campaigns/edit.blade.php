@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-edit text-blue-600"></i> Edit Campaign
        </h1>
        <a href="{{ route('admin.campaigns.index') }}"
           class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 px-4 py-2 rounded-lg transition">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl border dark:border-gray-700 p-6 max-w-5xl mx-auto">
        <form action="{{ route('admin.campaigns.update', $campaign->id) }}"
              method="POST"
              enctype="multipart/form-data"
              id="editForm">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- ==== TITLE ==== --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Title<span class="text-red-500">*</span></label>
                    <input type="text" name="title"
                           value="{{ old('title', $campaign->title) }}"
                           class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                    @error('title')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- ==== LOCATION ==== --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Location<span class="text-red-500">*</span></label>
                    <input type="text" name="location"
                           value="{{ old('location', $campaign->location) }}"
                           class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                    @error('location')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- ==== CATEGORY ==== --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="campaign_category_id"
                            class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600"
                            required>
                        <option value="" disabled>Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('campaign_category_id', $campaign->campaign_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('campaign_category_id')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ==== GOAL AMOUNT ==== --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Goal Amount (₦)<span class="text-red-500">*</span></label>
                    <input type="number" name="goal_amount"
                           value="{{ old('goal_amount', $campaign->goal_amount) }}"
                           class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                    @error('goal_amount')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- ==== START DATE ==== --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Start Date<span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="start_date"
                        value="{{ old('start_date', \Carbon\Carbon::parse($campaign->start_date)->format('Y-m-d\TH:i')) }}"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                    @error('start_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- ==== END DATE ==== --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">End Date</label>
                    <input type="date" name="end_date"
                        value="{{ old('end_date', $campaign->end_date ? \Carbon\Carbon::parse($campaign->end_date)->format('Y-m-d') : '') }}"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                    @error('end_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- ==== SHORT DESCRIPTION ==== --}}
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Short Description<span class="text-red-500">*</span></label>
                <textarea name="short_description" rows="3"
                          class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">{{ old('short_description', $campaign->short_description) }}</textarea>
                @error('short_description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- ==== MINIMUM DONATION ==== --}}
                    <div>
    <label class="block text-gray-600 dark:text-gray-300 mb-2 font-medium">
        Minimum Donation (₦) <span class="text-red-500">*</span>
    </label>
    <input type="number" step="0.01" name="minimum_donation" 
           value="{{ old('minimum_donation', $campaign->minimum_donation) }}" required
           
           class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary @error('minimum_donation') border-red-500 @enderror">
    @error('minimum_donation')
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>

            {{-- ==== FULL DESCRIPTION ==== --}}
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Full Description<span class="text-red-500">*</span></label>
                <textarea name="full_description" rows="6"
                          class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">{{ old('full_description', $campaign->full_description) }}</textarea>
                @error('full_description')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            {{-- ==== VIDEO URL ==== --}}
<div class="mt-6">
    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
        Video URL <span class="text-gray-500 text-xs font-normal">(Optional – YouTube, Vimeo, etc.)</span>
    </label>
    <input type="url" name="video_url"
           value="{{ old('video_url', $campaign->video_url) }}"
           placeholder="https://www.youtube.com/watch?v=..."
           class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
    <p class="text-xs text-gray-500 mt-2">
        Embed a video to make your campaign more engaging. Supported: YouTube, Vimeo, etc.
    </p>
    @error('video_url')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror

            {{-- ==== CHECKBOXES ==== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="is_featured"
                           @checked(old('is_featured', $campaign->is_featured)) class="h-4 w-4">
                    Featured Campaign
                </label>

                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="is_urgent"
                           @checked(old('is_urgent', $campaign->is_urgent)) class="h-4 w-4">
                    Urgent Campaign
                </label>

                <label class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="recurring_donations"
                           @checked(old('recurring_donations', $campaign->recurring_donations)) class="h-4 w-4">
                    Allow Recurring Donations
                </label>
            </div>

            {{-- ==== FEATURED IMAGE ==== --}}
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Featured Image</label>
                @if($campaign->featured_image)
                    <img src="{{ asset('storage/'.$campaign->featured_image) }}"
                         class="h-40 rounded-lg shadow-md mb-3 object-cover">
                @endif
                <input type="file" name="featured_image" class="block w-full text-sm text-gray-700 dark:text-gray-300">
                @error('featured_image')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- ==== GALLERY IMAGES ==== --}}
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gallery Images</label>

                <div id="existingGallery" class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
                    @foreach($campaign->gallery_images as $img)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $img) }}"
                                 class="h-24 w-full object-cover rounded-lg border dark:border-gray-600">
                            <button type="button"
                                    onclick="removeExistingImage(this, '{{ $img }}')"
                                    class="absolute top-1 right-1 bg-red-600 text-white text-xs p-1 rounded opacity-0 group-hover:opacity-100 transition">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    @endforeach
                </div>

                <input type="file" name="gallery_images[]" id="galleryInput" multiple class="block w-full text-sm">
                <div id="newGalleryPreview" class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-3"></div>
            </div>

            {{-- ==== STATUS ==== --}}
            <div class="mt-6">
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">Status</label>
                <select name="status"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
                    <option value="draft"     {{ $campaign->status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active"    {{ $campaign->status == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="completed" {{ $campaign->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            {{-- SUBMIT BUTTON --}}
            <div class="flex justify-end mt-8">
                <button type="submit"
                        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-8 py-3 rounded-lg shadow-lg transition-all">
                    <i class="fas fa-save"></i> Update Campaign
                </button>
            </div>

        </form>
    </div>
</div>

{{-- JS for Gallery Preview --}}
<script>
    document.getElementById('galleryInput').addEventListener('change', function (e) {
        const preview = document.getElementById('newGalleryPreview');
        preview.innerHTML = '';

        [...e.target.files].forEach(file => {
            if (!file.type.startsWith('image/')) return;
            const reader = new FileReader();
            reader.onload = ev => {
                const div = document.createElement('div');
                div.className = 'relative group';
                div.innerHTML = `
                    <img src="${ev.target.result}" class="h-24 w-full object-cover rounded-lg border">
                    <button type="button" onclick="this.parentElement.remove()"
                            class="absolute top-1 right-1 bg-red-600 text-white text-xs p-1 rounded opacity-0 group-hover:opacity-100 transition">
                        <i class="fas fa-times"></i>
                    </button>`;
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    });

    window.removeExistingImage = function (btn, path) {
        if (!confirm('Remove this image?')) return;
        btn.parentElement.remove();

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'delete_gallery[]';
        input.value = path;
        document.getElementById('editForm').appendChild(input);
    };
</script>
@endsection
