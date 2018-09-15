<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index()->comment('设计师id');
            $table->decimal('amount')->comment('金额');
            $table->string('number')->comment('流水号');
            $table->string('bank')->nullable()->comment('收款银行');
            $table->string('name')->nullable()->comment('收款人姓名');
            $table->dateTime('remitted_at')->nullable()->comment('汇款时间');
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
        Schema::dropIfExists('payments');
    }
}
