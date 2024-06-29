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
        Schema::dropIfExists('parent_category');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('parent_category', function (Blueprint $table) {
            $table->id();
            $table->integer('u_id')
                ->comment('ユーザーid');
            $table->string('name')
                ->comment('名前');
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
