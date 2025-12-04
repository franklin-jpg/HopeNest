@extends('layouts.admin')

@section('content')
<main class="max-w-6xl mx-auto py-8 space-y-6">

    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.campaigns.index')}}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg shadow">
            <i class="fa fa-arrow-left"></i>
            Back to Campaigns
        </a>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-white">
            Campaign Categories
        </h1>

        <div class="flex items-center gap-3">
            <!-- View Archived Button -->
            <a href="{{ route('admin.categories.archived') }}"
               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg shadow flex items-center gap-2">
                <i class="fa fa-archive"></i>
                Archived {{ $archivedCount }}
            </a>

            <!-- Add Category Button -->
            <a href="{{ route('admin.categories.create') }}"
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow flex items-center gap-2">
                <i class="fa fa-plus"></i>
                Add Category
            </a>
        </div>
    </div>

    <!-- Search + Filters -->
    <form method="GET" action="{{ route('admin.categories.index') }}"
          class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex flex-col md:flex-row gap-4 items-center justify-between">

        <div class="w-full md:w-1/2">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search categories..."
                   class="w-full px-3 py-2 rounded-lg border dark:border-gray-700 dark:bg-gray-900 dark:text-white">
        </div>

        <div class="w-full md:w-1/4">
            <select name="status"
                    class="w-full px-3 py-2 rounded-lg border dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                <option value="">Filter by Status</option>
                <option value="active"   {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="flex gap-2">
            <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow">
                <i class="fa fa-search mr-1"></i> Apply
            </button>

            @if(request()->has('search') || request()->has('status'))
                <a href="{{ route('admin.categories.index') }}"
                   class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg shadow">
                    <i class="fa fa-times mr-1"></i> Reset
                </a>
            @endif
        </div>
    </form>

    @if (session('success'))
        <div class="p-3 bg-green-100 text-green-700 rounded-md shadow">
            {{ session('success') }}
        </div>
    @endif

    <!-- Scrollable TABLE WRAPPER -->
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 mt-4 overflow-x-auto">

        <table class="w-full text-left min-w-[700px]">
            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                <tr>
                    <th class="p-4">Image</th>
                    <th class="p-4">Name</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Created</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($categories as $category)
                    <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        <!-- IMAGE -->
                        <td class="p-4">
                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}"
                                     class="w-12 h-12 rounded-lg object-cover shadow">
                            @else
                                <div class="w-12 h-12 flex items-center justify-center rounded-lg bg-gray-200 dark:bg-gray-600 text-gray-500">
                                    <i class="fa fa-image"></i>
                                </div>
                            @endif
                        </td>

                        <!-- NAME -->
                        <td class="p-4 font-semibold text-gray-800 dark:text-gray-200">
                            {{ $category->name }}
                        </td>

                        <!-- STATUS -->
                        <td class="p-4">
                            <span class="px-3 py-1 text-sm rounded-full font-medium
                                {{ $category->status === 'active'
                                    ? 'bg-green-200 text-green-800 dark:bg-green-700 dark:text-white'
                                    : 'bg-red-200 text-red-800 dark:bg-red-700 dark:text-white' }}">
                                {{ ucfirst($category->status) }}
                            </span>
                        </td>

                        <!-- CREATED DATE -->
                        <td class="p-4 text-gray-600 dark:text-gray-300">
                            {{ $category->created_at->format('M d, Y') }}
                        </td>

                        <!-- ACTION BUTTONS -->
                        <td class="p-4 text-right space-x-2">

                            <!-- EDIT -->
                            <a href="{{ route('admin.categories.edit', $category->slug) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm shadow">
                                <i class="fa fa-edit"></i>
                            </a>

                            <!-- ARCHIVE -->
                            <form action="{{ route('admin.categories.archive', $category->slug)}}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg text-sm shadow"
                                        onclick="return confirm('Archive this category?')">
                                    <i class="fa fa-archive"></i>
                                </button>
                            </form>

                            <!-- DELETE -->
                            <form action="{{ route('admin.categories.destroy', $category->slug) }}"
                                  method="POST"
                                  class="inline-block"
                                  onsubmit="return confirm('Delete this category?');">
                                @csrf
                                @method('DELETE')

                                <button class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm shadow">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-6 text-center text-gray-500 dark:text-gray-300">
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

    <div class="mt-4">
        {{ $categories->appends(request()->query())->links() }}
    </div>

</main>
@endsection
