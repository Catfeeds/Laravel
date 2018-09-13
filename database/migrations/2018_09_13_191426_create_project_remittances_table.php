<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectRemittancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_remittances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->unsigned()->index();
            $table->decimal('amount')->comment('金额');
            $table->string('number')->comment('汇款号');
            $table->string('bank')->nullable()->comment('银行');
            $table->string('name')->nullable()->comment('汇款人姓名');
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
        Schema::dropIfExists('project_remittances');
    }
}
