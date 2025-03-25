<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerificationMail;
use Illuminate\Support\Str;



class AuthenticatedSessionController extends Controller
{

    public function create()
    {
        return view('auth.login'); // 独自のログインビュー
    }


    public function store(LoginRequest $loginRequest)
    // public function store(Request $request)
    {
        // 認証情報を取得
        $credentials = $loginRequest->only('email', 'password');
        // $credentials = $request->only('email', 'password');

        // 認証処理
        if (Auth::attempt($credentials)) {

            $user = Auth::user();

            // dd($user);

            // ▼▼▼▼▼▼▼▼▼▼▼▼

            // メール認証が未完了の場合、再度認証メールを送信
            // if ($user->email_verification_token) {
            // メール認証トークンがある場合、再送信
            Mail::to($user->email)->send(new EmailVerificationMail($user));
            // }

            // メール認証後にプロフィール設定画面にリダイレクト（必ずメール認証が必要）
            // return redirect()->route('profile.create');

            //   ▲▲▲▲▲▲▲▲▲▲▲▲

            // ▼▼▼▼▼▼▼▼▼▼▼▼

            // return view('auth.login-message');

            // return back()->with(['login-message' => 'ログインを完了するには、メール認証が必要です。認証メールが届きます。メール本文内のリンクをクリックして、メール認証を完了してください。']);
            return to_route('login')->with(['login-message' => 'ログインを完了するには、メール認証が必要です。認証メールが届きます。メール本文内のリンクをクリックして、メール認証を完了してください。']);

            //   ▲▲▲▲▲▲▲▲▲▲▲▲

        }

        // 認証失敗の場合
        // return back()->withErrors(['email' =>  'ログイン情報が登録されていません。']);

        // return back()->with(['error' => 'ログイン情報が登録されていません。']);// PHPUnitテストで、エラーとなるため、return to_route()に書き換え
        return to_route('login')->with(['error' => 'ログイン情報が登録されていません。']);

        // return redirect()->route('login')->with('error',  'ログイン情報が登録されていません。');

        // }
    }


    /**
        * ユーザーが認証された後のリダイレクト先を変更
        */
    // protected function authenticated(Request $request, $user)
    // {
    // ユーザーがすでに認証メールを送信されていない場合
    // if (!$user->email_verification_token) {
    // トークンを生成して保存
    // $user->email_verification_token = Str::random(60);
    // $user->save();

    // メール送信（ユーザーに認証メールを送信）
    // Mail::to($user->email)->send(new EmailVerificationMail($user));
    // }

    // 初回ログインの場合はプロフィール登録ページにリダイレクト
    // if ($user->is_first_login) {
    //     $user->is_first_login = false; // 初回ログインフラグを false に変更
    // $user->save(); // 更新を保存

    // return redirect()->route('profile.create'); // プロフィール登録ページにリダイレクト
    // }

    // それ以外の場合は、元々アクセスしようとしていたページにリダイレクト
    // return redirect()->intended();
    // }




    public function destroy(Request $request)
    {
        Auth::logout();

        // セッションを削除
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route("items.index"); // ログアウト後、ホームにリダイレクト
    }
}
