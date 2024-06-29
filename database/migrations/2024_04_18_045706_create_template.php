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
        Schema::create('template', function (Blueprint $table) {
            $table->id();
            $table->integer('u_id')
                ->comment('ユーザーid');
            $table->string('name')
                ->comment('名前');
            $table->integer('cat')
                ->comment('カテゴリ');
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
        Schema::dropIfExists('template');
    }
};
