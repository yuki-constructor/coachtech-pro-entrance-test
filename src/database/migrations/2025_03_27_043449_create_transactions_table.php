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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id');
            $table->string('status')->default(0); // 0: 取引中, 1: 取引完了   取引状態フラグ
            $table->dateTime('buyer_completed_at')->nullable(); // 購入者が取引完了ボタンを押下した日
            $table->dateTime('seller_completed_at')->nullable(); // 出品者が購入者を評価した日
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
