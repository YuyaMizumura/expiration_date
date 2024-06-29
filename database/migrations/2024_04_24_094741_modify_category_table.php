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
        Schema::table('category', function (Blueprint $table) {
            // 'del'カラムの削除
            $table->dropColumn('del');
            
            // 'deleted_at'カラムの追加（論理削除用）
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category', function (Blueprint $table) {
            // 'deleted_at'カラムの削除
            $table->dropSoftDeletes();
            
            // 'del'カラムの追加（削除済みの値を格納するカラム）
            $table->boolean('del')->default(0);
        });
    }
};
