<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id'); 
            $table->string('email')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('last_name');
            $table->date('birthday');
            $table->uuid('verification_token');
            $table->dateTime('verified_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('verification_token', 'users_idx_verification_token');
        });
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
