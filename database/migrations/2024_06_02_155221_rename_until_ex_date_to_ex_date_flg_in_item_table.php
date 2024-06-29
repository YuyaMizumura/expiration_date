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
            // カラムの名前を変更し、タイプをbooleanに変更する
            $table->boolean('until_ex_date')->change();
            $table->renameColumn('until_ex_date', 'ex_date_flg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('item', function (Blueprint $table) {
            // カラム名を元に戻す
            $table->renameColumn('ex_date_flg', 'until_ex_date');
            
            // カラムのタイプを元に戻す（必要に応じて、ここではintegerに戻す例）
            $table->integer('until_ex_date')->change();
        });
    }
};
