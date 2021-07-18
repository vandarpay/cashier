<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVandarWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vandar_withdrawals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('authorization_id');
            $table->string('withdrawal_id')->nullable();
            $table->decimal('amount', 20, 0)->comment('Cuurency : RIAL');;
            $table->decimal('wage_amount', 20, 0)->nullable()->comment('Cuurency : RIAL');
            $table->boolean('is_instant')->default(true);
            $table->integer('retry_count')->nullable();
            $table->integer('max_retry_count')->default(1);
            $table->string('gateway_transaction_id')->nullable();
            $table->string('payment_number')->nullable();
            $table->string('status')->nullable();
            $table->string('description')->nullable();
            $table->date('withdrawal_date');
            $table->string('withdrawal_date_jalali')->nullable();
            $table->integer('error_code')->nullable();
            $table->json('error_messages')->nullable();
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
        Schema::dropIfExists('vandar_withdrawals');
    }
}
