<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('logged_by');
            $table->string('location')->nullable()->after('ip_address');
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn(['ip_address', 'location']);
        });
    }
};
