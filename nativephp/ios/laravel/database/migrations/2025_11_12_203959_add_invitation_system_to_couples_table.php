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
        Schema::table('couples', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('couples', 'partner_one_id')) {
                $table->foreignId('partner_one_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('couples', 'partner_two_id')) {
                $table->foreignId('partner_two_id')->nullable()->constrained('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('couples', 'invitation_code')) {
                $table->string('invitation_code')->unique()->nullable();
            }
            if (!Schema::hasColumn('couples', 'status')) {
                $table->enum('status', ['pending', 'waiting_for_partner', 'active'])->default('pending');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('couples', function (Blueprint $table) {
            $table->dropColumn(['partner_one_id', 'partner_two_id', 'invitation_code', 'status']);
        });
    }
};
