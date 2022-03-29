<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginSaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class UserLoginController extends Controller
{
    public function index()
    {
        return view('mypage.login');
    }

    public function login(UserLoginSaveRequest $request)
    {
        $data = $request->validated();

        if (!auth()->attempt($data)) {
            throw ValidationException::withMessages(['email' => 'メールアドレスかパスワードが間違っています。']);
        }

        return redirect()->route('mypage.blog');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login')->with(['message' => 'ログアウトしました。']);
    }

    public function confirm(User $user)
    {
        return view('mypage.delete', compact('user'));
    }

    // public function destroy(User $user, UserLoginSaveRequest $request)
    // {
    //     if ($request->user()->isNot($user)) {
    //         abort(403);
    //     }

    //     $data = $request->validated();

    //     if (!auth()->attempt($data)) {
    //         throw ValidationException::withMessages(['email' => 'メールアドレスかパスワードが間違っています。']);
    //     }

    //     try {
    //         DB::beginTransaction();
    //         $user->delete();
    //         DB::commit();
    //     } catch (Throwable $e) {
    //         DB::rollBack();
    //     }

    //     return redirect()->route('login')->with(['message' => 'アカウントを削除しました。']);
    // }
}
