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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // unsigned bigint
            $table->string('name'); // ユーザー名
            $table->string('email')->unique(); // メールアドレス（Fortify用）
            $table->string('password'); // パスワード（Fortify用）
            $table->string('profile_image')->nullable(); // プロフィール画像（オプション）
            $table->string('postal_code')->nullable(); // 郵便番号（オプション）
            $table->string('address')->nullable(); // 住所（オプション）
            $table->string('building')->nullable(); // 建物名（オプション）
            $table->string('email_verification_token')->nullable()->unique(); //メール認証用
            $table->boolean('is_first_login')->default(true); // 初回ログインフラグ
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
