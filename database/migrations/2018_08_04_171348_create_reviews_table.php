<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->default(0)->index();
            $table->integer('requirement_id')->unsigned()->default(0)->index();
            $table->integer('user_id')->unsigned()->default(0)->index()->comment('被评价的用户');
            $table->integer('reviewer_id')->unsigned()->default(0)->index()->comment('写评价的用户');
            $table->string('content')->comment('评价内容');
            $table->string('additional_content')->nullable()->comment('追加评价');
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
        Schema::dropIfExists('reviews');
    }
}
