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
        Schema::create('expiration', function (Blueprint $table) {
            $table->id();
            $table->integer('u_id');
            $table->integer('i_id');
            $table->integer('exp_id');
            $table->string('name');
            $table->integer('cat_id');
            $table->date('date');
            $table->boolean('complete_flg')->default(false)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expiration');
    }
};
