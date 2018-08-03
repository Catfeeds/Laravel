<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            // 当 user_id 对应的 users 表数据被删除时，删除动态
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        Schema::table('replies', function (Blueprint $table) {
            // 当 user_id 对应的 users 表数据被删除时，删除此条数据
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // 当 topic_id 对应的 topics 表数据被删除时，删除此条数据
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            // 移除外键约束
            $table->dropForeign(['user_id']);
        });
        Schema::table('replies', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['activity_id']);
        });
    }
}
