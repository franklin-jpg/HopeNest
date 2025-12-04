<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('impact_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->nullable()->constrained('campaigns')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 200);
            $table->text('content');
            $table->string('featured_image');
            $table->string('beneficiary_name')->nullable();
            $table->string('beneficiary_location')->nullable();
            $table->date('impact_date')->nullable();
            $table->json('metrics')->nullable(); // For storing impact metrics like "500 meals served", "10 families helped"
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impact_stories');
    }
};