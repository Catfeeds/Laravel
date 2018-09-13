<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemittanceToProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('remittance', 1000)->nullable()->comment('甲方的汇款信息，由甲方填写');
            $table->dateTime('remittance_submitted_at')->nullable()->comment('汇款信息填写于');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['remittance', 'remittance_submitted_at']);
        });
    }
}
