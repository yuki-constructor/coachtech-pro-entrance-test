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
        Schema::create('purchases', function (Blueprint $table) {

            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // 購入したユーザー
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // 購入された商品
            $table->tinyInteger('purchase_status')->default(0); // 0: pending, 1: completed   購入済みフラグ
            $table->tinyInteger('payment_method'); // 1: credit_card, 2: convenience_store 　支払い方法
            $table->string('stripe_payment_id'); // StripeのトランザクションID
            $table->string('sending_address'); // 送付先住所
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
