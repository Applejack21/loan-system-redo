<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::table('equipments', function (Blueprint $table) {
			$table->dropConstrainedForeignId('category_id');
		});


		// make pivot table for category and equipment model
		Schema::create('category_equipment', function (Blueprint $table) {
			$table->foreignId('category_id')->constrained('categories');
			$table->foreignId('equipment_id')->constrained('equipments');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('category_equipment');

		Schema::table('equipments', function (Blueprint $table) {
			$table->foreignId('category_id')->nullable()->after('last_updated_by_user_id')->constrained('categories');
		});
	}
};
