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
		Schema::create('loans', function (Blueprint $table) {
			$table->id();
			$table->foreignId('created_by_user_id')->constrained('users');
			$table->foreignId('last_updated_by_user_id')->constrained('users');
			$table->foreignId('approved_by_user_id')->nullable()->constrained('users');
			$table->foreignId('denied_by_user_id')->nullable()->constrained('users');
			$table->foreignId('loanee_id')->constrained('users');
			$table->string('status')->default('Requested');
			$table->string('denied_reason')->nullable();
			$table->dateTime('approval_date')->nullable();
			$table->datetime('start_date');
			$table->dateTime('end_date');
			$table->dateTime('date_returned')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::create('equipment_loans', function (Blueprint $table) {
			$table->foreignId('equipment_id')->constrained('equipments');
			$table->foreignId('loan_id')->constrained('loans');
			$table->integer('quantity')->default(1);
			$table->boolean('returned')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('equipment_loans');
		Schema::dropIfExists('loans');
	}
};
