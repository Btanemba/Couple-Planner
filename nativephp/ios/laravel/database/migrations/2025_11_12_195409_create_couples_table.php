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
        Schema::create('couples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_one_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('partner_two_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('partner_one_name');
            $table->string('partner_two_name');
            $table->date('relationship_start_date');
            $table->text('relationship_description')->nullable();
            $table->string('anniversary_reminder')->default('monthly');
            $table->json('milestones')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('invitation_code')->unique()->nullable();
            $table->enum('status', ['pending', 'accepted', 'active'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('couples');
    }
};
