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
        Schema::create('deleted_memos', function (Blueprint $table) {
            $table->id();
            $table->boolean('force_deleted');
            $table->unsignedBigInteger('memo_id')->comment('メモID');
            $table->unsignedBigInteger('user_id')->comment('ユーザーID');
            $table->unsignedBigInteger('category_id')->comment('カテゴリーID');
            $table->string('title', 100)->comment('タイトル');
            $table->string('body', 3000)->comment('メモの内容');
            $table->unsignedTinyInteger('status')->default(0)->comment('記事のステータス');
            $table->boolean('is_inappropriate')->default(false)->comment('内容が不適切か');
            $table->unsignedTinyInteger('reviewed_by')->nullable()->comment('誰に審査されたか');
            $table->dateTime('reviewed_at')->nullable()->comment('審査された日時');
            $table->unsignedTinyInteger('status_at_review')->nullable()->comment('審査された時点での記事のステータス');
            $table->boolean('fixed_after_warning')->nullable()->comment('警告後に修正されたか');
            $table->dateTime('approved_at')->nullable()->comment('修正を承認された日時');
            $table->dateTime('memo_created_at')->comment('メモ作成日時');
            $table->dateTime('memo_updated_at')->comment('メモ更新日時');
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
        Schema::dropIfExists('deleted_memos');
    }
};
