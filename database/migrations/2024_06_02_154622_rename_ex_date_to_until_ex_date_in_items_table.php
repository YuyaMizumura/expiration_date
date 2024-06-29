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
        Schema::table('item', function (Blueprint $table) {
            $table->integer('ex_date')->change();
            
            // カラム名を変更する
            $table->renameColumn('ex_date', 'until_ex_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item', function (Blueprint $table) {
            // カラム名を元に戻す
            $table->renameColumn('until_ex_date', 'ex_date');

            // カラムのタイプを元に戻す（必要に応じて）
            $table->string('ex_date')->change();
        });
    }
};
