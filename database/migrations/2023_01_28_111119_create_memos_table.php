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
            $table->boolean('is_appropriate')->default(true)->comment('内容が適切か');
            $table->unsignedTinyInteger('reviewed_by')->nullable()->comment('誰に審査されたか');
            $table->dateTime('reviewed_at')->nullable()->comment('審査された日時');
            $table->unsignedTinyInteger('status_at_review')->nullable()->comment('審査された時点での記事のステータス');
            $table->boolean('fixed_after_warning')->nullable()->comment('警告後に修正されたか');
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
