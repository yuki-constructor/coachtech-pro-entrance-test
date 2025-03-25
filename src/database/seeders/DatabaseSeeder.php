<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ユーザーダミーデータを10件挿入
        $this->call(UsersTableSeeder::class);

        // ItemsTableSeeder を呼び出す
        $this->call(ItemsTableSeeder::class);
    }
}
