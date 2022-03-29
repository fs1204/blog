<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
/**
 * @see \App\Http\Controllers\SignUpController
 */
class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test index */
    function ユーザー登録画面を開ける()
    {
        $this->get('signup')
            ->assertOk();
    }

    /** @test store */
    function 不正なデータではユーザー登録できない()
    {
        App::setlocale('testing');

        $url = 'signup';

        $this->post($url, ['name' => '',])->assertInvalid(['name' => 'required']);
        $this->post($url, ['name' => str_repeat('あ', 21)])->assertInvalid(['name' => 'max']);
        $this->post($url, ['name' => str_repeat('あ', 20)])->assertValid('name');

        $this->post($url, ['email' => ''])->assertInvalid(['email' => 'required']);
        $this->post($url, ['email' => 'aa@bb@cc'])->assertInvalid(['email' => 'email']);
        $this->post($url, ['email' => 'あああ@いい'])->assertInvalid(['email' => 'email']);
        User::factory()->create(['email' => 'aaa@bbb.net']);
        $this->post($url, ['email' => 'aaa@bbb.net'])->assertInvalid(['email' => 'unique']);

        $this->post($url, ['password' => '',])->assertInvalid(['password' => 'required']);
        $this->post($url, ['password' => str_repeat('a', 7)])->assertInvalid(['password' => 'min']);
        $this->post($url, ['password' => str_repeat('a', 8)])->assertValid('password');

        $this->from($url)->post($url, [])->assertRedirect($url);
    }

    /** @test store */
    function ユーザー登録できる()
    {
        $validData = User::factory()->validData();
        $this->post('signup', $validData)->assertRedirect('/mypage/blogs');

        unset($validData['password']);
        $this->assertDatabaseHas('users', $validData);

        $user = User::firstWhere($validData);
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('abcd1234', $user->password));

        $this->assertAuthenticatedAs($user);
    }
}
