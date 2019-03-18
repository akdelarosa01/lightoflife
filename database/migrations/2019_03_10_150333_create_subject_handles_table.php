<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubjectHandlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_handles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('id_number');
            $table->string('teacher_name');
            $table->integer('user_id');
            $table->integer('dept_id');
            $table->integer('subject_id');
            $table->integer('program_id');
            $table->string('program');
            $table->integer('section_id');
            $table->string('create_user');
            $table->string('update_user');
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
        Schema::dropIfExists('subject_handles');
    }
}
