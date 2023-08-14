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
        Schema::create('firebase_logins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
//                ->constrained()
//                ->onUpdate('cascade')
//                ->onDelete('restrict')
                ->comment('ユーザーID');
            $table->string('firebase_uid', 255)->nullable()->comment('Firebaseのuid');
            $table->string('token_id', 255)->nullable()->comment('IDトークン(oauth_access_tokensテーブルのidカラム参照)');
            $table->text('access_token')->nullable()->comment('Firebaseのアクセストークン');
            $table->dateTime('expires_at')->nullable();
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
        Schema::dropIfExists('firebase_logins');
    }
};
