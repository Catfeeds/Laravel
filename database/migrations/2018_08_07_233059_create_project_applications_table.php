<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0);
            $table->integer('project_id')->unsigned()->default(0);
            $table->string('remark', 300)->nullable()->comment('说明文字');
            $table->string('application_file_url')->nullable()->comment('附件链接');
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
        Schema::dropIfExists('project_applications');
    }
}
