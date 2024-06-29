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
            // `complete` カラムを削除
            $table->dropColumn('complete');
            // `price` カラムを追加 (整数型)
            $table->integer('price')->after('name')->nullable()->default(0);
            // `tax` カラムを追加 (浮動小数点型)
            $table->float('tax')->after('price')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expiration_date', function (Blueprint $table) {
            // `complete` カラムを再作成
            $table->boolean('complete')->nullable();
            // `price` カラムを削除
            $table->dropColumn('price');
            // `tax` カラムを削除
            $table->dropColumn('tax');
        });
    }
};
