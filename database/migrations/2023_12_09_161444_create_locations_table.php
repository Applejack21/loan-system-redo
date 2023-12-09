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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
			$table->foreignId('created_by_user_id')->constrained('users');
			$table->foreignId('last_updated_by_user_id')->constrained('users');
			$table->string('name');
			$table->string('room_code')->nullable()->comment('If the location has a room code, e.g. FL1/12 (floor 1, room 12)');
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
