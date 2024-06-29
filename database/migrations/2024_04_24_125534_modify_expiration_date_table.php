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
        Schema::table('expiration_date', function (Blueprint $table) {
            // 'del'カラムを削除
            $table->dropColumn('del');
            
            // 'deleted_at'カラムを追加
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expiration_date', function (Blueprint $table) {
            // 'del'カラムを追加（元の状態に戻すため）
            $table->integer('del')->default(0);

            // 'deleted_at'カラムを削除
            $table->dropSoftDeletes();
        });
    }
};
