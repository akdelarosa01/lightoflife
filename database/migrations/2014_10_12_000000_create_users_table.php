<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->string('lastname');
            $table->string('password');
            $table->string('actual_password');
            $table->enum('user_type', [1, 2, 3, 4])->default(1);
            $table->integer('is_admin')->length(1)->default(0);
            $table->integer('is_deleted')->length(1)->default(0);
            $table->rememberToken();
            $table->timestamps();
        });

        User::create([
            'username' => 'admin',
            'firstname' => 'System',
            'middlename' => '',
            'lastname' => 'Administrator',
            'password' => '$2y$10$IOJMSaeoJVM0m1mEx.38Lu9Ds4zhCeCUPrAiXah/nJqfqBT9bce9i',
            'actual_password' => 'admin01',
            'user_type' => 1,
            'is_admin' => 1,
            'is_deleted' => 0,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
