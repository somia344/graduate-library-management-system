<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('return_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_book_id')->constrained()->onDelete('cascade');
            $table->date('return_date');
            $table->integer('days_overdue')->default(0);
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_books');
    }
};