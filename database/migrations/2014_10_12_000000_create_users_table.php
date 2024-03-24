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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firebase_uid')->nullable();
            $table->string('name')->nullable();
            $table->string('nickname')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->unsignedTinyInteger('inappropriate_posts_count')->default(0)->comment('不適切と判断された投稿の回数');
            $table->unsignedTinyInteger('total_times_notified_to_fix')->default(0)->comment('修正依頼通知回数の合計');
            $table->unsignedTinyInteger('total_times_delete_memos_by_admin')->default(0)->comment('管理者によって記事が削除された回数');
            $table->unsignedTinyInteger('times_warned')->default(0)->comment('警告回数');
            $table->rememberToken();
            $table->timestamps(); // created_at, updated_at
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
};
