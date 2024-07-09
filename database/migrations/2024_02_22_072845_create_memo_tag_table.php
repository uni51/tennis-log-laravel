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
        Schema::create('memo_tag', function (Blueprint $table) {
            $table->foreignId('memo_id')->references('id')->on('memos');
            $table->foreignId('tag_id')->references('id')->on('tags');
            $table->timestamps();

            $table->primary(['memo_id', 'tag_id']);
            // $table->index(['memo_id', 'tag_id'], 'i_tag_fwd');
            $table->index(['tag_id', 'memo_id'], 'i_tag_rev');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_tag');
    }
};
