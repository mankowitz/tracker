<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEncountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('encounters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('provider_id')->constrained('users');
            $table->string("chief_complaint")->nullable();            
            $table->string("location")->nullable();
            $table->text("comments")->nullable();            
            $table->tinyInteger("is_complete")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('encounters');
    }
}
