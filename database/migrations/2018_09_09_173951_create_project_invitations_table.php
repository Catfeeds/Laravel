<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_invitations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('status')->default(0)->comment('邀请状态: 0未读，1接受，2拒绝');
            $table->integer('invited_user_id')->unsigned()->index();
            $table->integer('project_id')->unsigned()->index();
            $table->string('refusal_cause', 300)->nullable()->comment('拒绝理由');
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
        Schema::dropIfExists('project_invitations');
    }
}
