<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0);
            $table->integer('status')->index()->comment('项目状态：500已取消，1000发布中，1100进行中，1200已完成');
            $table->string('types', 300)->comment('什么类型的项目，格式array；这里存储英文，前端对应中英文的翻译。如果是业主填的"其他"，则直接显示出来，不翻译');
            $table->string('title')->comment('项目标题');
            $table->string('features', 1000)->comment('项目功能，格式array');
            $table->text('area')->comment('项目面积，比如多大、绿化率等');
            $table->text('description')->comment('项目其他描述，如施工预算、动工时间、项目风格等等');
            $table->string('project_file_url')->nullable()->comment('附件链接');
            $table->string('delivery_time', 300)->comment('交付时间：一个月后、三个月后、六个月后、其他。前三个有翻译，第四个是时间字符串');
            $table->string('payment')->comment('希望支付给设计师的费用');
            $table->text('supplement_description')->nullable()->comment('补充说明');
            $table->string('supplement_file_url')->nullable()->comment('补充附件链接');
            $table->string('supplement_at')->nullable()->comment('补充时间');
            $table->string('find_time')->comment('希望用多长时间找设计师');
            $table->text('remark')->nullable()->comment('申请备注');
            $table->string('canceled_at')->nullable()->comment('取消时间');
            $table->integer('favorite_count')->unsigned()->default(0)->comment('收藏人数');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
