<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorite_campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->boolean('notify_when_close')->default(false);
            $table->boolean('notified')->default(false);
            $table->timestamps();
            
            // Prevent duplicate favorites
            $table->unique(['user_id', 'campaign_id']);
            
            // Indexes
            $table->index('user_id');
            $table->index('campaign_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorite_campaigns');
    }
};