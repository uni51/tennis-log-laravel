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
        Schema::create('bookmark_memo', function (Blueprint $table) {
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('memo_id')->references('id')->on('memos');
            $table->timestamps();

            $table->unique(['user_id', 'memo_id']);
            // $table->index(['user_id', 'memo_id'], 'i_memo_fwd');
            $table->index(['memo_id', 'user_id'], 'i_memo_rev');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookmark_memos');
    }
};
