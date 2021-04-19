<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInviteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_invite', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->default('0')->comment('用户');
            $table->bigInteger('invite_id')->default('0')->comment('被邀请用户');
            $table->integer('channel_id')->comment('渠道');
            $table->smallInteger('level')->default('1')->comment('层级');
            $table->boolean('activity')->comment('激活');
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
        Schema::dropIfExists('user_invite');
    }
}
