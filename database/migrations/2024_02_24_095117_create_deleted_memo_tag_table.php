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
        Schema::create('deleted_memo_tag', function (Blueprint $table) {
            $table->foreignId('memo_id');
            $table->foreignId('tag_id');
            $table->dateTime('memo_tag_created_at')->comment('作成日時');
            $table->dateTime('memo_tag_updated_at')->comment('更新日時');
            $table->timestamps();
            $table->boolean('is_force_deleted')->default(false)->comment('強制削除されたかどうか');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deleted_memo_tag');
    }
};
