<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVandarPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vandar_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token');
            $table->morphs('vandar_payment');
            $table->decimal('amount', 20, 0);
            $table->decimal('real_amount', 20, 0);
            $table->enum('status', ['PENDING', 'SUCCEED', 'FAILED'])->comment('0:PENDING 1:SUCCEED 2:FAILED');
            $table->unsignedBigInteger('user_id');
            $table->string('mobile_number');
            $table->string('transId');
            $table->string('factorNumber');
            $table->string('description');
            $table->string('valid_card_number');
            $table->string('cardNumber');
            $table->string('cid')->comment('SHA256');
            $table->dateTime('paymentDate');
            $table->string('message');
            $table->string('errors');
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
        Schema::dropIfExists('vandar_payment');
    }
}
