<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->default(0)->index()->comment('谁上传的图片');
            $table->enum('type', ['avatar', 'activity_photo', 'project_file', 'application_file', 'business_license', 'id_card', 'work_photo'])->index()->comment('头像；动态图片；项目附件；报名附件；营业执照；身份证照片；作品集照片');
            $table->string('path')->index();
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
        Schema::dropIfExists('uploads');
    }
}
