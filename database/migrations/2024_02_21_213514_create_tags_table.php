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
            $table->boolean('is_appropriate')->default(true)->comment('内容が適切か');
            $table->foreignId('reviewed_by')->nullable()->comment('誰に審査されたか');
            $table->dateTime('reviewed_at')->nullable()->comment('審査された日時');
            $table->unsignedTinyInteger('status_at_review')->nullable()->comment('審査された時点でのタグのステータス');
            $table->boolean('fixed_after_warning')->nullable()->comment('警告後に修正されたか');
            $table->dateTime('approved_at')->nullable()->comment('修正を承認された日時');
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
