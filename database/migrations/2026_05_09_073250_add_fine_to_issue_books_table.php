<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('issue_books', function (Blueprint $table) {
    if (!Schema::hasColumn('issue_books', 'fine')) {
        $table->decimal('fine', 10, 2)->default(0)->after('status');
    }
});
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('issue_books', function (Blueprint $table) {
            //
        });
    }
};
