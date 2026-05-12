<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('issue_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->date('issue_date');
            $table->date('return_date');
            $table->enum('status', ['issued', 'returned'])->default('issued');
            $table->decimal('fine_amount', 8, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['student_id', 'status']);
            $table->index(['book_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('issue_books');
    }
};