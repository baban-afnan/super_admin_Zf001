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
        Schema::table('bvn_user', function (Blueprint $table) {
            // Modifying status column to include all necessary enum values
            // We use 'change()' to modify the existing column
            $table->enum('status', [
                'pending',
                'processing',
                'in-progress',
                'resolved',
                'successful',
                'query',
                'rejected',
                'failed',
                'remark'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bvn_user', function (Blueprint $table) {
            // Reverting to a simpler state if needed, though exact original state is unknown
            // Assuming a safe fallback or leaving as string if unsure, 
            // but for 'down' we usually try to reverse 'up'.
            // without knowing original exact definition, this is a best-effort reverse.
            // Ideally we would revert to the previous enum list, but since we don't know it,
            // we will just leave it or revert to string to be safe.
            // However, strict reversal requires knowing the previous state.
            // Let's assume the previous state was missing 'rejected' etc.
            // For safety, we can make it a string in down() to avoid data loss if rolled back.
            $table->string('status')->change();
        });
    }
};
