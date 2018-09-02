<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->string('phone');
            $table->string('email')->unique()->nullable();
            $table->boolean('email_activated')->default(false);
            $table->string('password');
            $table->enum('type', ['party', 'designer', 'expert'])->comment('用户类型：party、designer、expert');
            $table->string('avatar_url')->nullable();
            $table->string('title')->nullable()->comment('个人职位');
            $table->string('introduction')->nullable()->comment('个人简介');
            $table->string('birthday')->nullable();
            $table->integer('follower_count')->unsigned()->default(0);
            $table->integer('following_count')->unsigned()->default(0);
            $table->tinyInteger('sex')->default(0)->comment('性别：0: 未设置；1: 男；2: 女');
            $table->integer('notification_count')->unsigner()->default(0)->comment('通知数');
            $table->text('qualification_urls')->nullable()->comment('资质证书链接数组');
            $table->string('id_number')->nullable();
            $table->string('id_card_url')->nullable();
            $table->string('bank_name')->nullable()->comment('开户行');
            $table->string('bank_card_number')->nullable()->comment('卡号');
            $table->string('account_name')->nullable()->comment('开户名');
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
        Schema::dropIfExists('users');
    }
}
