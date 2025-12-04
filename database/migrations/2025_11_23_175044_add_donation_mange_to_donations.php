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
        Schema::table('donations', function (Blueprint $table) {
             $table->boolean('tax_certificate_sent')->default(false);
   $table->timestamp('tax_certificate_sent_at')->nullable();
   $table->string('recurring_status')->nullable(); // active, paused, cancelled
   $table->timestamp('recurring_paused_at')->nullable();
   $table->timestamp('recurring_cancelled_at')->nullable();
   $table->string('refund_reason')->nullable();
   $table->timestamp('refunded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
               $table->dropColumn([
                'tax_certificate_sent',
                'tax_certificate_sent_at',
                'recurring_status',
                'recurring_paused_at',
                'recurring_cancelled_at',
                'refunded_at',
            ]);
        });
    }
};
