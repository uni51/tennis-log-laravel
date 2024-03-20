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
            $table->boolean('is_not_tennis_related')->default(false)->comment('テニスに関連していない'); // ●
            $table->boolean('is_inappropriate')->default(false)->comment('内容が不適切か');
            $table->boolean('is_waiting_for_admin_review')->default(false)->comment('審査待ちかどうか'); // ●
            $table->boolean('is_waiting_for_fix')->default(false)->comment('修正待ちかどうか');
            $table->unsignedTinyInteger('reviewed_by')->nullable()->comment('誰に審査されたか。ChatGPT:1、管理者:2'); // ●
            $table->dateTime('reviewed_at')->nullable()->comment('審査された日時'); // ●
            $table->unsignedTinyInteger('status_at_review')->nullable()->comment('審査に引っかかった時点での記事のステータス'); // ●
            $table->boolean('fixed_after_warning')->default(false)->comment('警告後に修正されたか');
            $table->unsignedTinyInteger('approved_by')->nullable()->comment('誰に承認されたか。ChatGPT:1、管理者:2');
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
