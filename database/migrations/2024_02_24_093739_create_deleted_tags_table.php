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
            $table->boolean('force_deleted');
            $table->unsignedBigInteger('tag_id')->comment('タグID');
            $table->string('name');
            $table->foreignId('created_by')->nullable();
            $table->boolean('created_by_admin')->comment('管理者が作成したタグかどうか');
            $table->boolean('is_appropriate')->comment('内容が適切か');
            $table->foreignId('reviewed_by')->nullable()->comment('誰に審査されたか');
            $table->dateTime('tag_created_at')->comment('タグ作成日時');
            $table->dateTime('tag_updated_at')->comment('タグ更新日時');
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
        Schema::dropIfExists('deleted_tags');
    }
};
