@extends('layouts.admin')

@section('content')
<main class="max-w-3xl mx-auto py-8 space-y-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Add New Category</h1>
        <a href="{{ route('admin.categories.index') }}"
           class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow transition">
            &larr; Back to Categories
        </a>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-6">
        @csrf

        <div>
            <label class="block text-gray-700 dark:text-gray-300 mb-1">Category Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:text-white">
            @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 dark:text-gray-300 mb-1">Status</label>
            <select name="status"
                    class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:text-white">
                <option value="">Select status</option>
                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            @error('status')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-gray-700 dark:text-gray-300 mb-1">Category Image</label>
            <input type="file" name="image"
                   class="w-full border rounded-lg px-3 py-2 dark:bg-gray-900 dark:text-white">
            @error('image')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">
                Submit
            </button>
        </div>
    </form>
</main>
@endsection
