<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;


class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // カテゴリーデータの挿入
        $categories = [
            'ファッション',
            '家電',
            'インテリア',
            'レディース',
            'メンズ',
            'コスメ',
            '本',
            'ゲーム',
            'スポーツ',
            'キッチン',
            'ハンドメイド',
            'アクセサリー',
            'おもちゃ',
            'ベビー・キッズ'
        ];

        foreach ($categories as $categoryName) {
            Category::create(['category_name' => $categoryName]);
        }

        // コンディションデータの挿入
        $conditions = [
            '良好',
            '目立った傷や汚れなし',
            'やや傷や汚れあり',
            '状態が悪い'
        ];

        foreach ($conditions as $conditionName) {
            Condition::create(['condition_name' => $conditionName]);
        }

        // アイテムデータの挿入
        $items = [
            [
                'user_id' => 1,
                'item_name' => '腕時計',
                'item_image' => 'Armani+Mens+Clock.jpg',
                'brand' => 'Armani',
                'price' => 15000,
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'categories' => ['メンズ', 'ファッション'],
                'conditions' => ['良好'],
            ],
            [
                'user_id' => 1,
                'item_name' => 'HDD',
                'item_image' => 'HDD+Hard+Disk.jpg',
                'brand' => 'BUFFALO',
                'price' => 5000,
                'description' => '高速で信頼性の高いハードディスク',
                'categories' => ['家電'],
                'conditions' => ['目立った傷や汚れなし'],
            ],
            [
                'user_id' => 1,
                'item_name' => '玉ねぎ3束',
                'item_image' => 'iLoveIMG+d.jpg',
                'brand' => '北海道ファーム',
                'price' => 300,
                'description' => '新鮮な玉ねぎ3束のセット',
                'categories' => ['キッチン'],
                'conditions' => ['やや傷や汚れあり'],
            ],
            [
                'user_id' => 1,
                'item_name' => '革靴',
                'item_image' => 'Leather+Shoes+Product+Photo.jpg',
                'brand' => 'Armani',
                'price' => 4000,
                'description' => 'クラシックなデザインの革靴',
                'categories' => ['ファッション'],
                'conditions' => ['状態が悪い'],
            ],
            [
                'user_id' => 1,
                'item_name' => 'ノートPC',
                'item_image' => 'Living+Room+Laptop.jpg',
                'brand' => 'sony',
                'price' => 45000,
                'description' => '高性能なノートパソコン',
                'categories' => ['家電'],
                'conditions' => ['良好'],
            ],
            [
                'user_id' => 2,
                'item_name' => 'マイク',
                'item_image' => 'Music+Mic+4632231.jpg',
                'brand' => 'エレコム',
                'price' => 8000,
                'description' => '高音質のレコーディング用マイク',
                'categories' => ['家電'],
                'conditions' => ['目立った傷や汚れなし'],
            ],
            [
                'user_id' => 2,
                'item_name' => 'ショルダーバッグ',
                'item_image' => 'Purse+fashion+pocket.jpg',
                'brand' => 'CHANEL',
                'price' => 3500,
                'description' => 'おしゃれなショルダーバッグ',
                'categories' => ['ファッション'],
                'conditions' => ['やや傷や汚れあり'],
            ],
            [
                'user_id' => 2,
                'item_name' => 'タンブラー',
                'item_image' => 'Tumbler+souvenir.jpg',
                'brand' => 'ニトリ',
                'price' => 500,
                'description' => '使いやすいタンブラー',
                'categories' => ['キッチン'],
                'conditions' => ['状態が悪い'],
            ],
            [
                'user_id' => 2,
                'item_name' => 'コーヒーミル',
                'item_image' => 'Waitress+with+Coffee+Grinder.jpg',
                'brand' => 'Panasonic',
                'price' => 4000,
                'description' => '手動のコーヒーミル',
                'categories' => ['キッチン'],
                'conditions' => ['良好'],
            ],
            [
                'user_id' => 2,
                'item_name' => 'メイクセット',
                'item_image' => '%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'brand' => '資生堂',
                'price' => 2500,
                'description' => '便利なメイクアップセット',
                'categories' => ['コスメ'],
                'conditions' => ['目立った傷や汚れなし'],
            ]
        ];

        foreach ($items as $itemData) {
            // アイテムを作成
            $item = Item::create([
                'user_id' => $itemData['user_id'],
                'item_name' => $itemData['item_name'],
                'item_image' => $itemData['item_image'],
                'brand' => $itemData['brand'],
                'price' => $itemData['price'],
                'description' => $itemData['description'],
            ]);

            // カテゴリを関連付ける
            $categoryIds = Category::whereIn('category_name', $itemData['categories'])->pluck('id')->toArray();
            $item->categories()->attach($categoryIds);

            // コンディションを関連付ける
            $conditionIds = Condition::whereIn('condition_name', $itemData['conditions'])->pluck('id')->toArray();
            $item->conditions()->attach($conditionIds);
        }
    }
}
