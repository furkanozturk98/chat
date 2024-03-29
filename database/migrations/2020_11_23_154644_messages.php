<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Messages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('from');
            $table->unsignedBigInteger('to')->nullable();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->string('room_id')->nullable();
            $table->text('message')->nullable();
            $table->string('image')->nullable();
            $table->unsignedTinyInteger('status')->nullable();
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->nullable();
            $table->unsignedInteger('deleted_by')->nullable();
            $table->softDeletes();

            $table->foreign('from')->references('id')->on('users');
            $table->foreign('to')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
