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
        Schema::table('event_categories', function (Blueprint $table) {
            $table->integer('min_age')->nullable()->after('price');
            $table->integer('max_age')->nullable()->after('min_age');
            $table->string('gender_restriction', 10)->nullable()->after('max_age');
            $table->foreignId('event_distance_id')->nullable()->constrained()->after('gender_restriction')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('event_distance_id');
            $table->dropColumn(['min_age', 'max_age', 'gender_restriction']);
        });
    }
};
