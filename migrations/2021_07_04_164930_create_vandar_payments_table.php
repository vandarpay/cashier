<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVandarPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vandar_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token');
            $table->nullableMorphs('vandar_payable', 'payable');
            $table->nullableMorphs('vandar_paymentable', 'paymentable');
            $table->decimal('amount', 20, 0);
            $table->decimal('real_amount', 20, 0)->nullable();
            $table->decimal('wage', 20, 0)->nullable();
            $table->enum('status', ['INIT', 'SUCCEED', 'FAILED'])->comment('0:INIT 1:SUCCEED 2:FAILED');
            $table->string('mobile_number')->nullable();
            $table->string('trans_id')->nullable();
            $table->string('ref_number')->nullable();
            $table->string('tracking_code')->nullable();
            $table->string('factor_number')->nullable();
            $table->string('description')->nullable();
            $table->string('valid_card_number')->nullable();
            $table->string('card_number')->nullable();
            $table->string('cid')->comment('SHA256')->nullable();
            $table->string('payment_start')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->string('message')->nullable();
            $table->string('errors')->nullable();
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
        Schema::dropIfExists('vandar_payments');
    }
}
