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
        // テーブル名の変更
        Schema::rename('expiration_date', 'expense');

        Schema::table('expense', function (Blueprint $table) {
            $table->json('item')->after('cat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense', function (Blueprint $table) {
            $table->dropColumn('item');
        });

        // テーブル名を `expense` から `expiration_date` に戻す
        Schema::rename('expense', 'expiration_date');
    }
};
