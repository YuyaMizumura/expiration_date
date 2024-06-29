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
        Schema::create('expiration_date', function (Blueprint $table) {
            $table->id();
            $table->integer('u_id')
                ->comment('ユーザーid');
            $table->string('name')
                ->comment('名前');
            $table->dateTime('date', precision: 0)
                ->comment('日付');
            $table->integer('cat')
                ->comment('カテゴリ');
            $table->longText('description')
                ->comment('備考')
                ->nullable();
            $table->string('img')
                ->comment('画像')
                ->nullable();
            $table->integer('del');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expiration_date');
    }
};
