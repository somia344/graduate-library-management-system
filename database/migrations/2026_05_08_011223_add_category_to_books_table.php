<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'category')) {
                $table->string('category')->nullable()->after('author');
            }
            
            // Remove category_id if exists
            if (Schema::hasColumn('books', 'category_id')) {
                $table->dropColumn('category_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->integer('category_id')->nullable();
        });
    }
};