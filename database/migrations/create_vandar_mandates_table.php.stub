<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVandarMandatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vandar_mandates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token');
            $table->string('authorization_id')->nullable();
            $table->string('name')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('email')->nullable();
            $table->integer('count');
            $table->decimal('limit', 20, 0)->comment('Cuurency : RIAL');
            $table->date('expiration_date');
            $table->string('bank_code');
            $table->string('status')->default('INIT');
            $table->boolean('is_active')->default(false);
            $table->json('errors')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vandar_mandates');
    }
}
