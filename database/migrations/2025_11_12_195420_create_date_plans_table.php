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
        Schema::create('date_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('planned_date');
            $table->string('location')->nullable();
            $table->string('category')->default('other'); // romantic, adventure, casual, special, other
            $table->decimal('estimated_cost', 8, 2)->nullable();
            $table->enum('status', ['planned', 'confirmed', 'completed', 'cancelled'])->default('planned');
            $table->text('notes')->nullable();
            $table->json('photos')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('date_plans');
    }
};
