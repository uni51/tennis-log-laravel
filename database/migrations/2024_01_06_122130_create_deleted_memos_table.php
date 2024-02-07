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
            $table->unsignedBigInteger('user_id')->comment('ユーザーID');
            $table->unsignedBigInteger('category_id')->comment('カテゴリーID');
            $table->string('title', 100)->comment('タイトル');
            $table->string('body', 3000)->comment('メモの内容');
            $table->unsignedTinyInteger('status')->default(0)->comment('記事のステータス: 0:下書き, 1:公開中, 2:シェア, 3:非公開, 4:削除');
            $table->boolean('force_deleted')->default(false);
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
