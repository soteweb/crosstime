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
        Schema::table('athletes', function (Blueprint $table) {
            $table->index('identity_document');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->index('bib_number');
            $table->index('status');
            $table->index('payment_status');
            $table->index(['event_id', 'status']); // Composite index for common filtered queries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropIndex(['identity_document']);
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex(['bib_number']);
            $table->dropIndex(['status']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['event_id', 'status']);
        });
    }
};
