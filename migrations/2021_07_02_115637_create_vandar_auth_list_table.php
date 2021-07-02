<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVandarAuthListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vandar_auth_list', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('token_type');
            $table->bigInteger('expires_in');
            $table->string('access_token');
            $table->string('refresh_token');

            $table->dateTime('created_at')->useCurrent = true;
            $table->dateTime('updated_at')->useCurrent = true;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vandar_auth_list');
    }
}
