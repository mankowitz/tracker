<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RedoNurseCaseMgrInEncounters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('encounters', function (Blueprint $table) {
            $table->dropColumn("nurse");
            $table->dropColumn("case_manager");
            $table->foreignId('nurse_id')->nullable()->constrained('users');
            $table->foreignId('case_manager_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('encounters', function (Blueprint $table) {
            //
        });
    }
}
