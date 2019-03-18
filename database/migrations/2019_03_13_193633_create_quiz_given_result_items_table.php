<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizGivenResultItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_given_result_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id');
            $table->integer('section_id');
            $table->integer('subject_id');
            $table->integer('teacher_id');
            $table->integer('quiz_id');
            $table->integer('question_num');
            $table->longText('question');
            $table->string('correct_answer');
            $table->string('student_answer');
            $table->double('score',20,2);
            $table->double('max_score',20,2);
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
        Schema::dropIfExists('quiz_given_result_items');
    }
}
