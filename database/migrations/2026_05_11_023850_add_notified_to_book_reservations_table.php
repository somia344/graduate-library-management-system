<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('book_reservations', function (Blueprint $table) {
            if (!Schema::hasColumn('book_reservations', 'notified')) {
                $table->boolean('notified')->default(0)->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('book_reservations', function (Blueprint $table) {
            $table->dropColumn('notified');
        });
    }
};