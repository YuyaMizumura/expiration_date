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
        Schema::table('expense', function (Blueprint $table) {
            // `price` カラムを `nullable` から `not nullable` に変更
            $table->integer('price')->nullable(false)->change();
            
            // `tax` カラムを `nullable` から `not nullable` に変更
            $table->float('tax')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expense', function (Blueprint $table) {
            // `price` カラムを `not nullable` から `nullable` に変更
            $table->integer('price')->nullable()->change();
            
            // `tax` カラムを `not nullable` から `nullable` に変更
            $table->float('tax')->nullable()->change();
        });
    }
};
