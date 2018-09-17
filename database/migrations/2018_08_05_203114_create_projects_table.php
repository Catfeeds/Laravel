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
            $table->integer('status')->index()->comment('项目状态：500已取消，600未通过审核，900审核中，1000发布中，1100工作中，1200已完成');
            $table->string('review_message', 500)->nullable()->comment('审核结果说明');
            $table->string('title')->comment('项目标题');
            $table->string('features', 1000)->comment('项目功能，格式array');
            $table->string('types', 300)->comment('项目类型，格式array；这里存储英文，前端对应中英文的翻译。如果是业主填的"其他"，则直接显示出来，不翻译');
            $table->string('keywords', 500)->nullable()->comment('关键字，格式array');
            $table->string('depth')->comment('设计深度要求');
            $table->text('description')->comment('项目描述，如项目面积、施工预算、动工时间、项目风格等等');
            $table->string('project_file_url')->nullable()->comment('附件链接');
            $table->string('delivery_time', 300)->comment('交付时间：一个月后、三个月后、六个月后、其他。前三个有翻译，第四个是时间字符串');
            $table->string('payment')->comment('希望支付给设计师的费用');
            $table->string('find_time')->comment('希望用多长时间找设计师');
            $table->text('remark')->nullable()->comment('申请备注');
            $table->enum('mode', ['free', 'invite', 'specify'])->comment('招标模式：自由，邀请，指定');
            $table->string('canceled_at')->nullable()->comment('取消时间');
            $table->string('completed_at')->nullable()->comment('完成时间');
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
