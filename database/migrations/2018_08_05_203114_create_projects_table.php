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
            $table->string('status')->index()->comment('项目状态：500已取消，1000发布中，1100进行中，1200已完成');
            $table->string('type')->comment('什么类型的项目，格式array；这里存储英文，前端对应中英文的翻译。如果是业主填的"其他"，则直接显示出来，不翻译');
            $table->string('feature')->comment('项目功能，格式array');
            $table->text('description')->comment('项目描述：包括项目面积，比如多大、绿化率等；项目风格');
            $table->string('file_url')->comment('附件链接');
            $table->string('delivery_time')->comment('交付时间：一个月后、三个月后、六个月后、其他。前三个有翻译，第四个是时间字符串');
            $table->string('degree')->comment('希望设计师做到哪种程度');
            $table->decimal('payment')->comment('希望支付给设计师的费用');
            $table->text('supplement_description')->comment('补充说明');
            $table->string('supplement_file_url')->comment('补充附件链接');
            $table->string('supplement_at')->comment('补充时间');
            $table->string('construction_time')->comment('动工时间');
            $table->decimal('construction_budget')->comment('施工预算');
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
