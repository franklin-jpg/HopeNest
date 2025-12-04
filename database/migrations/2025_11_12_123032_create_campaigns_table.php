<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_category_id')->constrained('campaign_categories')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('short_description', 150);
            $table->text('full_description');
            $table->string('location')->nullable();

            $table->decimal('goal_amount', 15, 2);
            $table->decimal('raised_amount', 15, 2)->default(0);
           $table->dateTime('start_date');

            $table->date('end_date')->nullable();
            $table->decimal('minimum_donation', 15, 2)->default(100);

            $table->string('featured_image');
            $table->string('gallery_images')->nullable();
            $table->string('video_url')->nullable();

            $table->boolean('is_featured')->nullable();
            $table->boolean('is_urgent')->nullable();
            $table->boolean('recurring_donations')->nullable();
            $table->text('custom_thank_you')->nullable();

            $table->enum('status', ['draft', 'active', 'completed'])->default('draft');

            $table->timestamps();
            $table->softDeletes();
        });

        
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
