<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('book_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('book_requests', 'admin_response')) {
                $table->text('admin_response')->nullable()->after('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('book_requests', function (Blueprint $table) {
            $table->dropColumn('admin_response');
        });
    }
};