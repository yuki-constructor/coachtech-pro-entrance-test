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
        Schema::create('transaction_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('user_id');
            $table->text('message')->nullable(); // 空のままでも保存可
            $table->string('image_path')->nullable(); // 画像があれば
            $table->boolean('is_read')->default(0); // 0: 未読, 1: 既読   既読フラグ
            // $table->boolean('is_sent')->default(0); // 0: 未送信, 1: 送信済み   送信フラグ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_messages');
    }
};
