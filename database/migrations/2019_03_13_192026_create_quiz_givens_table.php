<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuizGivensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_givens', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id');
            $table->integer('section_id');
            $table->integer('subject_id');
            $table->integer('quiz_id');
            $table->string('quiz_title');
            $table->string('quiz_type');
            $table->integer('teacher_id');
            $table->date('start_date');
            $table->time('start_time');
            $table->date('due_date');
            $table->time('due_time');
            $table->integer('timer');
            $table->integer('max_attempt');
            $table->integer('user_attempt')->default(0);
            $table->longText('instruction');
            $table->integer('late_submission');
            $table->double('max_score',20,2);
            $table->string('status');
            $table->integer('create_user');
            $table->integer('update_user');
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
        Schema::dropIfExists('quiz_givens');
    }
}
