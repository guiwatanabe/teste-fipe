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
        Schema::create('vehicle_model_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_model_id')->constrained('vehicle_models', 'id', 'vehicle_model_years_vehicle_model_id')->onDelete('cascade');
            $table->string('model_year_code');
            $table->string('model_year_name');
            $table->text('model_year_notes')->nullable();
            $table->foreignId('update_user')->constrained('users', 'id', 'vehicle_model_years_update_user')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_model_years');
    }
};
