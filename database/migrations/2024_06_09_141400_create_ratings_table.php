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
		Schema::create('ratings', function (Blueprint $table) {
			$table->id();
			$table->foreignId('created_by_user_id')->constrained('users');
			$table->foreignId('last_updated_by_user_id')->constrained('users');
			$table->foreignId('equipment_id')->constrained('equipment');
			$table->decimal('rating', 2, 1); // 2 total number, 1 after decimal point. (e.g. 0.0, 0.5, 1.0, 1.5, etc.)
			$table->longText('content')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('ratings');
	}
};
