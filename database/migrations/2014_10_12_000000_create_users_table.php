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
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->enum('type', ['party', 'designer', 'expert'])->comment('用户类型：party、designer、expert');
            $table->string('avatar_url')->nullable();
            $table->string('title')->nullable()->comment('个人职位');
            $table->string('introduction')->nullable()->comment('个人简介');
            $table->string('birthday')->nullable();
            $table->integer('follower_count')->unsigned()->default(0);
            $table->integer('following_count')->unsigned()->default(0);
            $table->tinyInteger('sex')->default(0)->comment('性别：0: 未设置；1: 男；2: 女');
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
