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
            $table->boolean('force_deleted');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('firebase_uid')->nullable();
            $table->string('name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('email');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->unsignedInteger('inappropriate_posts_count')->default(0)->comment('不適切な投稿の回数');
            $table->unsignedTinyInteger('times_warned')->comment('警告回数');
            $table->rememberToken();
            $table->dateTime('user_created_at')->comment('ユーザー作成日時');
            $table->dateTime('user_updated_at')->comment('ユーザー更新日時');
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
