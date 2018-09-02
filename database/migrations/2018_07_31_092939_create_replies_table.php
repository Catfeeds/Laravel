<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('activity_id')->unsigned()->default(0)->index();
            $table->integer('user_id')->unsigned()->default(0)->index()->comment('发表评论的人');
            $table->integer('reply_id')->unsigned()->nullable()->index()->comment('被回复的评论的id');
            $table->integer('root_reply_id')->unsigned()->nullable()->index()->comment('被回复的一级评论的id');
            $table->string('content');
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
        Schema::drop('replies');
    }
}
