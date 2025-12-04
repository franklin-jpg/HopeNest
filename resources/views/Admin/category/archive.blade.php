@extends('layouts.admin')

@section('content')
<div class="p-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-archive"></i>
            Archived Categories
        </h1>

        <a href="{{ route('admin.categories.index') }}"
           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow text-center">
            <i class="fas fa-arrow-left"></i> Active Categories {{ $activeCount }}
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif


    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 mb-6">
        <form action="{{ route('admin.categories.archived') }}" method="GET" 
              class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <!-- Search -->
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search category..."
                       class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
            </div>

            <!-- Status -->
            <div>
                <label class="text-sm text-gray-600 dark:text-gray-300">Status</label>
                <select name="status"
                        class="w-full px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">All</option>
                    <option value="active"   {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <!-- Apply -->
            <div class="flex items-end">
                <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg w-full">
                    Apply Filters
                </button>
            </div>

            <!-- Reset -->
            @if(request()->filled('search') || request()->filled('status'))
                <div class="flex items-end">
                    <a href="{{ route('admin.categories.archived') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg w-full text-center">
                        Reset
                    </a>
                </div>
            @endif

        </form>
    </div>


    <!-- Table -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-xl">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">

            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold">Image</th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold">Name</th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold">Status</th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold">Archived On</th>
                    <th class="px-4 sm:px-6 py-3 text-left text-xs font-semibold">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($categories as $category)
                    <tr>

                        <!-- Image -->
                        <td class="px-4 sm:px-6 py-4">
                            @if($category->image)
                                <img src="{{ asset('storage/'.$category->image) }}"
                                     class="w-10 h-10 sm:w-12 sm:h-12 rounded object-cover">
                            @else
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-300 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>

                        <!-- Name -->
                        <td class="px-4 sm:px-6 py-4 text-gray-800 dark:text-gray-200">
                            {{ $category->name }}
                        </td>

                        <!-- Status -->
                        <td class="px-4 sm:px-6 py-4">
                            <span class="px-2 py-1 rounded text-xs
                                {{ $category->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ ucfirst($category->status) }}
                            </span>
                        </td>

                        <!-- Archived at -->
                        <td class="px-4 sm:px-6 py-4 text-gray-600 dark:text-gray-300">
                            {{ $category->deleted_at->format('M d, Y') }}
                        </td>

                        <!-- Actions -->
                        <td class="px-4 sm:px-6 py-4">
                            <div class="flex flex-col sm:flex-row gap-2">

                                <!-- Restore -->
                                <form action="{{ route('admin.categories.restore', $category->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs w-full">
                                        Restore
                                    </button>
                                </form>

                                <!-- Delete -->
                                <form action="{{ route('admin.categories.forceDelete', $category->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs w-full"
                                            onclick="return confirm('Permanently delete this category?')">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-6 text-gray-500 dark:text-gray-300">
                            No archived categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $categories->appends(request()->query())->links() }}
    </div>

</div>
@endsection
