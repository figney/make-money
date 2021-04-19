<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->index()->default('')->comment('类型');
            $table->longText('title')->comment('标题');
            $table->longText('describe')->comment('描述');
            $table->string('cover')->default('')->comment('封面');
            $table->longText('content')->comment('内容');
            $table->smallInteger('order')->default('0')->comment('排序');
            $table->boolean('status')->default('1')->comment('状态');
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
        Schema::dropIfExists('articles');
    }
}
