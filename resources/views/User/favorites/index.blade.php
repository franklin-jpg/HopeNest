@extends('layouts.user')

@section('content')
<div class="max-w-7xl mx-auto p-6">
    
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Favorite Campaigns</h2>
                <p class="text-gray-600 dark:text-gray-400 mt-1">Campaigns you've saved for later</p>
            </div>
            <a href="{{ route('all.campaigns') }}" 
               class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                <i class="fas fa-plus mr-2"></i>
                Browse Campaigns
            </a>
        </div>
    </div>

    @if($favoriteCampaigns->isEmpty())
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-heart text-4xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white mb-2">No Favorite Campaigns Yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Start saving campaigns that matter to you</p>
            <a href="{{ route('all.campaigns') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                <i class="fas fa-search"></i>
                <span>Explore Campaigns</span>
            </a>
        </div>
    @else
        <!-- Campaigns Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($favoriteCampaigns as $campaign)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-shadow group">
                <!-- Campaign Image -->
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ asset('storage/' . $campaign->featured_image) }}" 
                         alt="{{ $campaign->title }}"
                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    
                    <!-- Category Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="px-3 py-1 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm text-xs font-semibold text-gray-800 dark:text-white rounded-full">
                            {{ $campaign->category->name ?? 'N/A' }}
                        </span>
                    </div>

                    <!-- Remove Favorite Button -->
                    <button onclick="removeFavorite({{ $campaign->id }})"
                            class="absolute top-3 right-3 w-8 h-8 bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-red-500 hover:text-white transition-colors group/btn">
                        <i class="fas fa-heart text-red-500 group-hover/btn:text-white"></i>
                    </button>

                    <!-- Urgent Badge -->
                    @if($campaign->is_urgent)
                    <div class="absolute bottom-3 right-3">
                        <span class="px-3 py-1 bg-red-500 text-white text-xs font-bold rounded-full animate-pulse">
                            🔥 URGENT
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Campaign Content -->
                <div class="p-5">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2 line-clamp-2 hover:text-primary transition-colors">
                        <a href="{{ route('campaigns.show', $campaign->slug) }}">
                            {{ $campaign->title }}
                        </a>
                    </h3>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                        {{ $campaign->short_description }}
                    </p>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <span class="font-semibold">{{ $campaign->progress_percentage }}% funded</span>
                            <span>₦{{ number_format($campaign->raised_amount) }}</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2.5">
                            <div class="bg-primary rounded-full h-2.5 transition-all" 
                                 style="width: {{ min($campaign->progress_percentage, 100) }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <span>Goal: ₦{{ number_format($campaign->goal_amount) }}</span>
                            @if($campaign->end_date)
                                <span>{{ $campaign->daysRemaining() }} days left</span>
                            @endif
                        </div>
                    </div>

                    <!-- Notification Toggle -->
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" 
                                   class="notification-toggle w-4 h-4 text-primary rounded focus:ring-2 focus:ring-primary"
                                   data-campaign-id="{{ $campaign->id }}"
                                   {{ $campaign->pivot->notify_when_close ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                <i class="fas fa-bell text-xs mr-1"></i>
                                Notify when close to goal
                            </span>
                        </label>
                        @if($campaign->pivot->notify_when_close && $campaign->progress_percentage >= 90)
                            <p class="text-xs text-green-600 dark:text-green-400 mt-1 ml-6">
                                <i class="fas fa-check-circle mr-1"></i>
                                Campaign is {{ $campaign->progress_percentage }}% funded!
                            </p>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-2">
                        <a href="{{ route('campaigns.show', $campaign->slug) }}" 
                           class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 text-center rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium">
                            View Details
                        </a>
                        <button onclick="quickDonate({{ $campaign->id }})"
                                class="flex-1 px-4 py-2 bg-primary text-white text-center rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium">
                            <i class="fas fa-heart mr-1"></i>
                            Donate Now
                        </button>
                    </div>

                    <!-- Saved Date -->
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 text-center">
                        Saved {{ \Carbon\Carbon::parse($campaign->pivot->created_at)->diffForHumans() }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $favoriteCampaigns->links() }}
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Quick Donate
    function quickDonate(campaignId) {
        window.location.href = `/donate/${campaignId}`;
    }

    // Remove Favorite
    async function removeFavorite(campaignId) {
        if (!confirm('Remove this campaign from favorites?')) return;

        try {
            const response = await fetch(`/user/campaigns/${campaignId}/favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();
            
            if (data.success) {
                iziToast.success({
                    title: 'Success',
                    message: data.message,
                    position: 'topRight'
                });
                
                setTimeout(() => location.reload(), 1000);
            }
        } catch (error) {
            console.error('Error:', error);
            iziToast.error({
                title: 'Error',
                message: 'Failed to remove from favorites',
                position: 'topRight'
            });
        }
    }

    // Notification Toggle
    document.querySelectorAll('.notification-toggle').forEach(toggle => {
        toggle.addEventListener('change', async function() {
            const campaignId = this.dataset.campaignId;
            const notifyWhenClose = this.checked;

            try {
                const response = await fetch(`/user/campaigns/${campaignId}/notification`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        notify_when_close: notifyWhenClose
                    })
                });

                const data = await response.json();
                
                if (data.success) {
                    iziToast.success({
                        title: 'Success',
                        message: data.message,
                        position: 'topRight'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                this.checked = !this.checked;
                iziToast.error({
                    title: 'Error',
                    message: 'Failed to update notification preference',
                    position: 'topRight'
                });
            }
        });
    });
</script>
@endpush
@endsection