<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizGivenResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_given_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('qgr_id');
            $table->integer('student_id');
            $table->integer('section_id');
            $table->integer('subject_id');
            $table->integer('teacher_id');
            $table->integer('quiz_id');
            $table->integer('quiz_given_id');
            $table->string('quiz_title');
            $table->string('quiz_type');
            $table->date('date_taken');
            $table->time('time_taken');
            $table->date('date_submitted');
            $table->time('time_submitted');
            $table->double('total_points',20,2);
            $table->double('max_score',20,2);
            $table->double('grade_percent',20,2);
            $table->integer('attempt_no');
            $table->longText('remarks');
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
        Schema::dropIfExists('quiz_given_results');
    }
}
