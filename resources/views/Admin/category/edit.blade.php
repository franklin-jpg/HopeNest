@extends('layouts.admin')

@section('content')
<main class="max-w-3xl mx-auto py-8 space-y-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Edit Category: {{ $category->name }}
        </h1>
        <a href="{{ route('admin.categories.index') }}"
           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow transition">
            &larr; Back to Categories
        </a>
    </div>

    <form action="{{ route('admin.categories.update', $category->slug) }}" method="POST"
          enctype="multipart/form-data"
          class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
        @csrf
        @method('PATCH')

        <div>
            <label class="block text-gray-700 dark:text-gray-300 mb-1">Category Name</label>
            <input type="text" name="name" value="{{ old('name', $category->name) }}"
                   class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:text-white">
            @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status"
                    class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:text-white">
                <option value="active" {{ $category->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $category->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

    <div>
    <label class="block text-gray-700 dark:text-gray-300 mb-1">Category Image</label>

    <div class="flex items-center gap-4">
        <!-- File Input -->
        <input type="file" name="image" id="imageInput"
               class="border rounded-lg px-3 py-2 dark:bg-gray-900 dark:text-white"
               accept="image/*">

        <!-- Remove Button -->
        <button type="button" id="removeImageBtn"
                class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 shadow"
                style="display: none;">
            Remove
        </button>
    </div>

    <!-- Preview Container -->
    <div class="mt-3 w-32 h-32 border rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
        <img id="imagePreview"
             src="{{ $category->image ? asset('storage/' . $category->image) : '' }}"
             class="w-full h-full object-cover {{ $category->image ? '' : 'hidden' }}">
        <!-- Placeholder Icon -->
        <i id="placeholderIcon" class="fas fa-image text-gray-400 text-3xl {{ $category->image ? 'hidden' : '' }}"></i>
    </div>

    @error('image')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>



        <div class="flex justify-end">
            <button class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg shadow">
                Update
            </button>
        </div>
    </form>
</main>
@endsection
@push('scripts')
    <script>
const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');
const removeBtn = document.getElementById('removeImageBtn');
const placeholderIcon = document.getElementById('placeholderIcon');

imageInput.addEventListener('change', function(event) {
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
            placeholderIcon.classList.add('hidden');
            removeBtn.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
    }
});

// Remove selected image
removeBtn.addEventListener('click', function() {
    imageInput.value = ''; // Clear file input
    imagePreview.src = '';
    imagePreview.classList.add('hidden');
    placeholderIcon.classList.remove('hidden');
    removeBtn.style.display = 'none';
});
</script>
@endpush


