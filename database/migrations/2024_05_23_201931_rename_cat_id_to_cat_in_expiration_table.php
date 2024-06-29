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
        Schema::table('expiration', function (Blueprint $table) {
            $table->renameColumn('cat_id', 'cat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expiration', function (Blueprint $table) {
            $table->renameColumn('cat', 'cat_id');
        });
    }
};
