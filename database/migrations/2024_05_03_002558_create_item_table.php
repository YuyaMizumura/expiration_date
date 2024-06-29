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
        Schema::create('item', function (Blueprint $table) {
            $table->id();

            // カラムの追加
            $table->integer('u_id')->unsigned(); // `u_id` カラム (整数型)
            $table->string('name'); // `name` カラム (文字列型)
            $table->integer('cat')->unsigned(); // `cat` カラム (整数型)
            $table->integer('price'); // `price` カラム (整数型)

            // ソフトデリートのための `deleted_at` カラム
            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item');
    }
};
