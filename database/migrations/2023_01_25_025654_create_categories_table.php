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
        // See. https://fuminori14.hatenablog.com/entry/20120920/1348147599
        //      https://thinkit.co.jp/free/tech/31/5?page=0%2C1
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // $table->integer('parent_id')->nullable(); // 親カテゴリID
            $table->string('name');
            $table->boolean('state')->default(true);
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
