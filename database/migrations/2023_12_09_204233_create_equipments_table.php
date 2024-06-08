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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by_user_id')->constrained('users');
            $table->foreignId('last_updated_by_user_id')->constrained('users');
            $table->foreignId('category_id')->constrained('categories');
            $table->foreignId('location_id')->constrained('locations');
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->longText('description')->nullable();
            $table->float('price')->comment('Price of equipment, used to help with customer loan damages to equipment.');
            $table->json('details')->nullable();
            $table->integer('amount')->comment('How many of this equipment we have.');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
