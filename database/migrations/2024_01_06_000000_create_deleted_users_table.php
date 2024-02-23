<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deleted_users', function (Blueprint $table) {
            $table->id();
            $table->boolean('force_deleted')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('firebase_uid')->nullable();
            $table->string('name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->unsignedTinyInteger('times_warned')->default(0)->comment('警告回数');
            $table->rememberToken();
            $table->dateTime('user_created_at')->nullable()->comment('ユーザー作成日時');
            $table->dateTime('user_updated_at')->nullable()->comment('ユーザー更新日時');
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
        Schema::dropIfExists('deleted_users');
    }
};
