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
        Schema::create('stock_item', function (Blueprint $table) {
            $table->id();

            // `u_id` カラム
            $table->integer('u_id');  // 必要に応じてインデックスを追加

            // `name` カラム
            $table->string('name');  // 文字列型

            // `cat` カラム
            $table->integer('cat');  // 必要に応じてインデックスを追加

            // `price` カラム
            $table->integer('price');  // 整数型

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_item');
    }
};
