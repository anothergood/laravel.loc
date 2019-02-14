<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('user_user', function (Blueprint $table) {
          $table->integer('user_initiator_id')->unsigned()->index();
          $table->integer('user_id')->unsigned()->index();
          $table->string('status',50)->index();;
          $table->timestamps();

          $table->foreign('user_initiator_id')->references('id')->on('users')->onDelete('cascade');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_user');
    }
}
