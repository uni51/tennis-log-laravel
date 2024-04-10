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
        Schema::create('deleted_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tag_id')->comment('タグID');
            $table->string('name');
            $table->foreignId('created_by')->nullable();
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
            $table->dateTime('tag_created_at')->comment('タグ作成日時');
            $table->dateTime('tag_updated_at')->comment('タグ更新日時');
            $table->timestamps();
            $table->boolean('is_force_deleted')->default(false)->comment('強制削除されたかどうか');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deleted_tags');
    }
};
