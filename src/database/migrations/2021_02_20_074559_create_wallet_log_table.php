<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('channel_id')->default('0')->comment('渠道');
            $table->bigInteger('user_id')->default('0')->comment('用户ID');
            $table->bigInteger('wallet_id')->default('0')->comment('钱包ID');
            $table->char('wallet_type')->comment('钱包类型');
            $table->char('action_type')->comment('操作类型');
            $table->decimal('fee')->default('0')->comment('变动的金额');
            $table->string('target_type')->default('')->comment('业务来源');
            $table->bigInteger('target_id')->default('0')->comment('业务来源ID');
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
        Schema::dropIfExists('wallet_log');
    }
}
