<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channel_service', function (Blueprint $table) {
            $table->increments('id');
            $table->string('channel_id')->default('')->comment('渠道ID');
            $table->string('type')->default('')->comment('客服类型');
            $table->string('name')->default('')->comment('客服名称');
            $table->string('avatar')->default('')->comment('客服头像');
            $table->string('url')->default('')->comment('客服联系地址');
            $table->boolean('status')->comment('状态');
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
        Schema::dropIfExists('channel_service');
    }
}
