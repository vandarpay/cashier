<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVandarRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vandar_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('trans_id');
            $table->string('business_name');
            $table->string('payment_number')->nullable();
            $table->string('refund_id')->nullable();
            $table->string('gateway_transaction_id')->nullable();
            $table->decimal('amount', 20, 0)->nullable();
            $table->decimal('wage', 20, 0)->nullable();
            $table->decimal('wallet', 20, 0)->nullable();
            $table->string('description')->nullable();
            $table->string('status')->nullable();
            $table->string('message')->nullable();
            $table->string('errors')->nullable();
            $table->string('refund_date_jalali')->nullable();
            $table->string('created_at_jalali')->nullable();
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
        Schema::dropIfExists('vandar_refunds');
    }
}
