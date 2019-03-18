<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHomeworkStudentAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('homework_student_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hw_id');
            $table->integer('student_id');
            $table->integer('section_id');
            $table->longText('answer')->nullable();
            $table->string('status');
            $table->datetime('date_given');
            $table->datetime('date_submitted')->nullable();
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
        Schema::dropIfExists('homework_student_answers');
    }
}
