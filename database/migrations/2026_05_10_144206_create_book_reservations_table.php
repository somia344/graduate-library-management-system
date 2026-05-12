<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('book_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->date('reservation_date');
            $table->date('expiry_date')->nullable();
            $table->integer('position')->default(0);
            $table->enum('status', ['pending', 'active', 'expired', 'cancelled', 'notified', 'issued'])->default('pending');
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index(['book_id', 'status']);
            $table->index(['student_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('book_reservations');
    }
};