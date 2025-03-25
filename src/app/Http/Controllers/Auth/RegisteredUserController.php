<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\EmailVerificationMail;



class RegisteredUserController extends Controller
{

    public function create()
    {
        return view('auth.register'); // 独自の会員登録ビュー
    }

    // public function store(RegisterRequest $registerRequest, AddressRequest $addressRequest,Request $request)
    // public function store(RegisterRequest $registerRequest,Request $request)
    public function store(RegisterRequest $registerRequest)
    // public function store(Request $request)
    {


         // ▼▼▼▼▼▼▼▼▼▼▼▼（フォームリクエストが適用できたら、削除）


        //  $validated = $request->validate([
            // 'name' => ['required', 'string'],             // お名前: 入力必須
            // 'email' => ['required', 'email', 'unique:users,email'],   // メールアドレス: 入力必須、メール形式
            // 'password' => ['required', 'string', 'min:8', 'confirmed'], // パスワード: 入力必須、8文字以上
            // 'password_confirmation' => ['required', 'string', 'min:8'], // パスワード: 入力必須、8文字以上、確認用パスワードとの一致
        // ]);

        // $user = User::create([
            // 'name' => $validated['name'],
            // 'email' => $validated['email'],
            // 'password' => Hash::make($validated['password']),
            // 'email_verification_token' => Str::random(60), // メール認証トークン生成
        // ]);


       //   ▲▲▲▲▲▲▲▲▲▲▲▲



       // ▼▼▼▼▼▼▼▼▼▼▼▼（フォームリクエストが適用できたら、復活させる）

        $user = User::create([

            // 'name' => $addressRequest->input('name'), // AddressRequest から 'name' を取得
            'name' => $registerRequest->input('name'), // RegisterRequest から 'name' を取得
            // 'name' => $request->input('name'), // Request から 'name' を取得

            'email' => $registerRequest->input('email'), // RegisterRequest から 'email' を取得
            // 'email' => $request->input('email'), // Request から 'email' を取得

            'password' => Hash::make($registerRequest->input('password')), // パスワードは RegisterRequest から取得してハッシュ化
            // 'password' => Hash::make($request->input('password')), // パスワードは Request から取得してハッシュ化

            // 'is_first_login' => 1, // Request から 'is_first_login' を取得

            'email_verification_token' => Str::random(60), // メール認証トークンを生成

        ]);

         //   ▲▲▲▲▲▲▲▲▲▲▲▲




        // 登録後にメール認証メールを送信
        // Mail::to($user->email)->send(new EmailVerificationMail($user));


        // // ユーザー登録後、ログイン画面にリダイレクト
        return redirect()->route('login');
        // return redirect()->route('login')->with('success', '登録が完了しました。');
    }
};
