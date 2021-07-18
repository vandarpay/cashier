<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVandarSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vandar_subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token');
            $table->string('subscription_code')->nullable();
            $table->integer('bank_code');
            $table->integer('count');
            $table->integer('limit');
            $table->string('name')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->date('expiration_date');
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
        Schema::dropIfExists('vandar_subscriptions');
    }
}
