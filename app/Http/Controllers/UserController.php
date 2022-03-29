<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class UserController extends Controller
{
    public function destroy(User $user, Request $request)
    {
        if ($request->user()->isNot($user)) {
            abort(403);
        }

        $data = $request->validate([
            'email' => ['required', 'email:filter'],
            'password' => ['required',],
        ]);

        if (!auth()->attempt($data)) {
            throw ValidationException::withMessages(['email' => 'メールアドレスかパスワードが間違っています。']);
        }

        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
        }

        return redirect()->route('login')->with(['message' => 'アカウントを削除しました。']);
    }
}
