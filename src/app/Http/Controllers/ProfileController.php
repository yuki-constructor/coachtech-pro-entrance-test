<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;



class ProfileController extends Controller
{
    // プロフィール設定画面を表示
    public function create()
    {
        return view('profile.create'); // プロフィール設定画面
    }

    // プロフィール登録処理
    public function store(ProfileRequest $profileRequest)
    {
        /** @var \App\Models\User $user */

        $user = Auth::user(); // ログイン中のユーザーを取得

        // プロフィール画像の保存（存在する場合のみ）
        $path = null;

        if ($profileRequest->hasFile('profile_image')) {
            // 画像をストレージに保存
            $path = basename($profileRequest->file('profile_image')->store('photos/profile_images', 'public'));
        }

        // プロフィール情報と住所情報をユーザーに保存
        $user->update([
            'name' => $profileRequest->input('name'),
            'profile_image' => $path,
            'postal_code' => $profileRequest->input('postal_code'),
            'address' => $profileRequest->input('address'),
            'building' => $profileRequest->input('building'),
        ]);

        return redirect()->route('item.mylist'); // 商品一覧画面（マイリスト）にリダイレクト

    }

    // プロフィール表示画面（出品した商品）
    public function showSell()
    {
        $user = Auth::user();
        // 出品した商品を取得
        $items = Item::where("user_id", $user->id)->get();

        // 出品した商品を表示
        return view('profile.show-sell', ['user' => $user, 'items' => $items]);
    }


    // プロフィール表示画面（購入した商品）
    public function showBuy()
    {
        $user = Auth::user();
        // 購入した商品を取得
        $purchases = $user->purchases()->get();
        // 購入した商品を表示
        return view('profile.show-buy', ['user' => $user, 'purchases' => $purchases]);
    }

    // プロフィール編集画面
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }


    // プロフィール更新処理
    public function update(ProfileRequest $profileRequest)
    {
        $user = Auth::user(); // ログイン中のユーザーを取得

        // プロフィール画像の保存（新しい画像が送信されている場合のみ）
        $path = $user->profile_image; // 初期値として既存の画像を使用
        if ($profileRequest->hasFile('profile_image')) {
            // 古い画像があれば削除
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // 新しい画像を保存
            $path = basename($profileRequest->file('profile_image')->store('photos/profile_images', 'public'));
        }

        // ユーザー情報の更新
        $user->update([
            'name' => $profileRequest->input('name'),
            'profile_image' => $path,
            'postal_code' => $profileRequest->input('postal_code'),
            'address' => $profileRequest->input('address'),
            'building' => $profileRequest->input('building'),
        ]);

        // プロフィール画面（購入した商品）にリダイレクト
        return redirect()->route('profile.show.buy');
    }


    // 住所編集画面
    public function editAddress($itemId)
    {
        $item = Item::findOrFail($itemId);

        return view('profile.edit-address', ['user' => Auth::user(), 'item' => $item]);
    }


    // 住所更新処理
    public function updateAddress(AddressRequest $addressRequest, $itemId)
    {
        $user = Auth::user(); // ログイン中のユーザーを取得
        $item = Item::findOrFail($itemId);

        // 住所情報の更新
        $user->update([
            'postal_code' => $addressRequest->input('postal_code'),
            'address' => $addressRequest->input('address'),
            'building' => $addressRequest->input('building'),
        ]);

        // 商品購入画面にリダイレクト
        return redirect()->route('item.purchase', ["itemId" => $item->id]);
    }
}
