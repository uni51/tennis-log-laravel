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
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->comment('ユーザーID')
                    ->constrained()
                    ->onUpdate('cascade');
            $table->foreignId('category_id')->comment('カテゴリーID')
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('restrict');
            $table->string('title', 100)->comment('タイトル');
            $table->string('body', 3000)->comment('メモの内容');
            $table->unsignedTinyInteger('status')->default(0)->comment('記事のステータス');
            $table->unsignedTinyInteger('chatgpt_review_status')->default(0)->comment('ChatGPTによる審査のステータス');
            $table->dateTime('chatgpt_reviewed_at')->nullable()->comment('ChatGPTに審査された日時');
            $table->unsignedTinyInteger('admin_review_status')->default(0)->comment('管理者による審査のステータス');
            $table->dateTime('admin_reviewed_at')->nullable()->comment('管理者に審査された日時');
            $table->unsignedTinyInteger('status_at_review')->nullable()->comment('審査に引っかかった時点での記事のステータス');
            $table->unsignedTinyInteger('times_notified_to_fix')->default(0)->comment('修正依頼通知回数');
            $table->unsignedTinyInteger('times_attempt_to_fix_after_notified')->default(0)->comment('通知後に修正を試みた回数');
            $table->dateTime('approved_at')->nullable()->comment('修正を承認された日時');
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
        Schema::dropIfExists('memos');
    }
};
