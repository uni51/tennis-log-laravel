<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('created_by')->nullable()->references('id')->on('users');
            $table->boolean('created_by_admin')->default(false)->comment('管理者が作成したタグかどうか');
            // $table->unsignedTinyInteger('status')->default(0)->comment('タグのステータス');
            // $table->unsignedTinyInteger('chatgpt_review_status')->default(0)->comment('ChatGPTによる審査のステータス');
            // $table->dateTime('chatgpt_reviewed_at')->nullable()->comment('ChatGPTに審査された日時');
            // $table->unsignedTinyInteger('admin_review_status')->default(0)->comment('管理者による審査のステータス');
            // $table->dateTime('admin_reviewed_at')->nullable()->comment('管理者に審査された日時');
            // $table->unsignedTinyInteger('status_at_review')->nullable()->comment('審査に引っかかった時点でのタグのステータス');
            // $table->unsignedTinyInteger('times_notified_to_fix')->default(0)->comment('修正依頼通知回数');
            // $table->unsignedTinyInteger('times_attempt_to_fix')->default(0)->comment('通知後に修正を試みた回数');
            // $table->unsignedTinyInteger('approved_by')->default(0)->comment('記事の承認者');
            // $table->dateTime('approved_at')->nullable()->comment('承認された日時');
            $table->timestamps();

            $table->unique(['name', 'created_by']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
