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
        Schema::table('users', function (Blueprint $table) {
            // 申請者IDsカラムの追加（複数の申請者IDを保存するための配列またはJSON形式）
            $table->json('applicant_ids')->nullable();

            // 共有者IDsカラムの追加（複数の共有者IDを保存するための配列またはJSON形式）
            $table->json('sharer_ids')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('applicant_ids');
            $table->dropColumn('sharer_ids');
        });
    }
};
