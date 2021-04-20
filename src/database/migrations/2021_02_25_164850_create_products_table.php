<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('big_cover')->default('')->comment('大图');
            $table->string('cover')->default('')->comment('封面');
            $table->json('content')->comment('内容');
            $table->integer('day_cycle')->comment('投资周期-天');
            $table->decimal('day_rate')->comment('日回报率-%');
            $table->json('describe')->comment('描述');
            $table->boolean('is_day_account')->comment('每日结算');
            $table->decimal('min_money')->comment('起投金额');
            $table->string('name')->default('')->comment('名称');
            $table->integer('order')->comment('排序');
            $table->json('select_money_list')->comment('快捷选择列表');
            $table->json('title')->comment('标题');
            $table->char('type')->comment('类型');
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
        Schema::dropIfExists('products');
    }
}
