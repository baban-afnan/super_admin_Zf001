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
        Schema::table('announcements', function (Blueprint $table) {
            // Update type enum to include 'advert'
            // In many DBs we can't easily change an enum, so we might need a raw query or add it if supported.
            // For simplicity and since Laravel 10+ handles change() better for some types, 
            // but for Enum it's safer to use raw or just add columns if we assume it's MySQL.
            
            $table->enum('type', ['email', 'announcement', 'advert'])->default('email')->change();
            $table->enum('recipient_type', ['all', 'role', 'single', 'manual_email', 'none', 'advert'])->nullable()->change();
            
            $table->string('image')->after('message')->nullable();
            $table->string('discount')->after('image')->nullable();
            $table->string('service_name')->after('discount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->enum('type', ['email', 'announcement'])->default('email')->change();
            $table->enum('recipient_type', ['all', 'role', 'single', 'manual_email', 'none'])->nullable()->change();
            $table->dropColumn(['image', 'discount', 'service_name']);
        });
    }
};
