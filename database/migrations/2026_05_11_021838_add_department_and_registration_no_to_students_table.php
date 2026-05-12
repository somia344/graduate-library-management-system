<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('students', 'department')) {
                $table->string('department')->nullable()->after('phone_number');
            }
            if (!Schema::hasColumn('students', 'registration_no')) {
                $table->string('registration_no')->nullable()->after('roll_no');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['department', 'registration_no']);
        });
    }
};