<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\Mypage\BlogController;
use App\Http\Controllers\Mypage\CommentController;
use App\Http\Controllers\Mypage\UserLoginController;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/blogs/{blog}', [HomeController::class, 'show'])->name('blog.show');

Route::get('signup', [SignUpController::class, 'index'])->name('signup');
Route::post('signup', [SignUpController::class, 'store']);

Route::middleware('guest')->group(function() {
    Route::get('mypage/login', [UserLoginController::class, 'index'])->name('login');
    Route::post('mypage/login', [UserLoginController::class, 'login']);
});

Route::middleware('auth')->group(function() {
    Route::post('mypage/logout', [UserLoginController::class, 'logout'])->name('mypage.logout');
    Route::get('mypage/delete/{user}', [UserLoginController::class, 'confirm'])->name('mypage.delete.index');
    Route::delete('mypage/delete/{user}', [UserController::class, 'destroy'])->name('mypage.delete');

    Route::get('mypage/blogs', [BlogController::class, 'index'])->name('mypage.blog');
    Route::get('mypage/blogs/create', [BlogController::class, 'create'])->name('mypage.blog.create');
    Route::post('mypage/blogs/create', [BlogController::class, 'store']);
    Route::get('mypage/blogs/edit/{blog}', [BlogController::class, 'edit'])->name('mypage.blog.edit');
    Route::post('mypage/blogs/edit/{blog}', [BlogController::class, 'update'])->name('mypage.blog.update');
    Route::delete('mypage/blogs/delete/{blog}', [BlogController::class, 'destroy'])->name('mypage.blog.delete');

    Route::post('/blogs/{blog}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/blogs/{comment}/destroy', [CommentController::class, 'destroy'])->name('comments.destroy');
});
