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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('destination');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('budget', 10, 2)->nullable();
            $table->decimal('spent_amount', 10, 2)->default(0);
            $table->enum('status', ['planning', 'booked', 'in_progress', 'completed', 'cancelled'])->default('planning');
            $table->json('itinerary')->nullable();
            $table->json('packing_list')->nullable();
            $table->json('photos')->nullable();
            $table->text('notes')->nullable();
            $table->string('accommodation')->nullable();
            $table->string('transportation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
