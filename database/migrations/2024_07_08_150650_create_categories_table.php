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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->comment('ユーザーID');
            $table->unsignedTinyInteger('career_id')->comment('テニス歴');
            $table->unsignedTinyInteger('gender_id')->comment('性別');
            $table->unsignedTinyInteger('dominantHand_id')->comment('利き手');
            $table->unsignedTinyInteger('playFrequency_id')->comment('プレイ頻度');
            $table->unsignedTinyInteger('tennisLevel_id')->comment('レベル');
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
        Schema::dropIfExists('categories');
    }
};
