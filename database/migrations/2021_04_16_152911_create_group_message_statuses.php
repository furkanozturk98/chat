<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMessageStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_message_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id')->constrained("groups");
            $table->foreignId('member_id')->constrained("users");
            $table->foreignId('message_id')->constrained("group_messages");
            $table->unsignedTinyInteger('status')->nullable();
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
        Schema::dropIfExists('group_message_statuses');
    }
}