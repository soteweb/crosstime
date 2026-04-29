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
        Schema::table('registrations', function (Blueprint $table) {
            $table->foreignId('event_distance_id')->nullable()->constrained()->after('event_category_id')->nullOnDelete();
            $table->string('tshirt_size', 10)->nullable()->after('event_distance_id');
            $table->string('payment_method')->nullable()->after('tshirt_size');
            $table->text('observation')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_distance_id');
            $table->dropColumn(['tshirt_size', 'payment_method', 'observation']);
        });
    }
};
