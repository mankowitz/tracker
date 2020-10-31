<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\UserType;

class CreateUserTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_types');
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string("short");
            $table->string("name");
        });
        

        UserType::create(['short'=>'prov', 'name'=>'Provider']);
        UserType::create(['short'=>'rn', 'name'=>'Nurse']);
        UserType::create(['short'=>'cm', 'name'=>'Case Manager']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_types');
    }
}
