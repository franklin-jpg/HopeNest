<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('currency', 3)->default('NGN')->after('amount'); // ISO 4217 currency code
            $table->decimal('exchange_rate', 10, 4)->default(1.0000)->after('currency'); // Rate to base currency
            $table->decimal('amount_in_base_currency', 15, 2)->nullable()->after('exchange_rate');
        });
        
        Schema::table('campaigns', function (Blueprint $table) {
            $table->json('supported_currencies')->nullable()->after('minimum_donation');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn(['currency', 'exchange_rate', 'amount_in_base_currency']);
        });
        
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('supported_currencies');
        });
    }
};