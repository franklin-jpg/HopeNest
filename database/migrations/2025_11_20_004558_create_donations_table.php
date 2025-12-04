<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Donor Information
            $table->string('donor_name');
            $table->string('donor_email');
            $table->string('donor_phone');
            $table->boolean('is_anonymous')->default(false);
            
            // Donation Details
            $table->decimal('amount', 15, 2);
            $table->enum('frequency', ['one-time', 'monthly', 'quarterly', 'yearly'])->default('one-time');
            $table->text('message')->nullable();
            $table->boolean('cover_fee')->default(false);
            $table->decimal('processing_fee', 15, 2)->default(0);
            $table->decimal('total_amount', 15, 2);
            
            // Payment Information
            $table->string('payment_method')->nullable(); // card, bank_transfer, ussd
            $table->string('payment_reference')->unique();
            $table->string('paystack_reference')->nullable();
            $table->string('transaction_id')->nullable();
            $table->enum('status', ['pending', 'successful', 'failed', 'refunded'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            
            // Receipt
            $table->string('receipt_number')->unique()->nullable();
            
            // Terms
            $table->boolean('agreed_to_terms')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['campaign_id', 'status']);
            $table->index('payment_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};