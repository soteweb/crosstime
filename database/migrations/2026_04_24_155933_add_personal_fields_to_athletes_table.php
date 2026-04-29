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
            $table->string('last_name')->after('name');
            $table->string('gender', 10)->after('last_name');
            $table->string('blood_type', 10)->after('gender')->nullable();
            $table->string('club')->after('blood_type')->nullable();
            $table->string('carnet_number')->after('club')->nullable();
            $table->date('date_of_birth')->after('carnet_number')->nullable();
            $table->string('city')->after('date_of_birth')->nullable();
            $table->string('country')->after('city')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('athletes', function (Blueprint $table) {
            $table->dropColumn(['last_name', 'gender', 'blood_type', 'club', 'carnet_number', 'date_of_birth', 'city', 'country']);
        });
    }
};
