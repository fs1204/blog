<?php

namespace Tests\Feature\Controllers\Mypage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
/**
 * @see \App\Http\Controllers\Mypage\UserLoginController
 */
class UserLoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test index */
    function 認証していない場合に限り、ログイン画面を開ける()
    {
        $this->get('mypage/login')->assertOk();

        $this->login();
        $this->get('mypage/login')
            ->assertRedirect('mypage/blogs');
    }

    /** @test login */
    function ログイン時の入力チェック()
    {
        $url = 'mypage/login';

        app()->setlocale('testing');

        $this->post($url, ['email' => ''])->assertInvalid(['email' => 'required']);
        $this->post($url, ['email' => 'aa@bb@cc'])->assertInvalid(['email' => 'email']);
        $this->post($url, ['email' => 'あああ@いい'])->assertInvalid(['email' => 'email']);

        $this->post($url, ['password' => '',])->assertInvalid(['password' => 'required']);

        $this->from($url)->post($url, [])->assertRedirect($url);
    }

    /** @test login */
    function ログインできる()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234'
        ];

        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);

        $this->post('mypage/login', $postData)
            ->assertRedirect('mypage/blogs'); //post送信が終わったら、mypageのblogsに転送されれればOK

        $this->assertAuthenticatedAs($user);
    }


    /** @test login */
    function IDを間違えているのでログインできない()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234'
        ];

        $dbData = [
            'email' => 'ccc@bbb.net',
            'password' => bcrypt('abcd1234')
        ];

        User::factory()->create($dbData);

        $url = 'mypage/login';

        $this->from($url)->post($url, $postData)
            ->assertRedirect($url);

        $this->get($url)
            ->assertSee('メールアドレスかパスワードが間違っています。');
    }

    /** @test login */
    function 認証エラーなのでValidationExceptionの例外が発生する()
    {
        $this->withoutExceptionHandling();

        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234'
        ];

        try {
            $this->post('mypage/login', $postData);
            $this->fail('validationExceptionの例外が発生しませんでした');
        } catch (ValidationException $e) {
            $this->assertEquals('メールアドレスかパスワードが間違っています。', $e->errors()['email'][0] ?? '');
        }
    }

    /** @test logout */
    function ログアウトできる()
    {
        $this->login();

        $this->post('mypage/logout')
        ->assertRedirect($url = 'mypage/login');

        $this->get($url)
            ->assertSee('ログアウトしました。');

        $this->assertGuest();
    }
}
