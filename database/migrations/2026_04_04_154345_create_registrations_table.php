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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->foreignId('event_category_id')->constrained();
            $table->foreignId('athlete_id')->constrained();
            $table->string('payment_status')->default('pending');
            $table->timestamp('waiver_accepted_at')->nullable();
            $table->timestamps();
            
            $table->unique(['event_id', 'athlete_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
