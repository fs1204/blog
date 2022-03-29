<?php

namespace Tests\Feature\Controllers\Mypage;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Mypage\BlogController
 */
class BlogControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function ゲストはブログを管理できない()
    {
        $url = 'mypage/login';

        $this->get('mypage/blogs')->assertRedirect($url);
        $this->get($url)->assertSee('ログイン</a>', false);
        $this->get('mypage/blogs/create')->assertRedirect($url);
        $this->post('mypage/blogs/create', [])->assertRedirect($url);
        $this->get('mypage/blogs/edit/1')->assertRedirect($url);
        $this->post('mypage/blogs/edit/1')->assertRedirect($url);
        $this->delete('mypage/blogs/delete/1')->assertRedirect($url);
    }

    /** @test index */
    function 認証している場合に限り、マイページを開ける()
    {
        $this->login();
        $this->get('mypage/blogs')
            ->assertOk()
            ->assertSee('ログアウト');
    }

    /** @test index */
    function マイページ、ブログ一覧で自分のデータのみ表示される()
    {
        $user = $this->login();  //認証済みのuserを受け取る

        $myblog = Blog::factory()->create(['user_id' => $user]);
        $other = Blog::factory()->create();

        $this->get('mypage/blogs')
            ->assertOk()
            ->assertDontSee($other->title)
            ->assertSee($myblog->title);
    }

    /** @test create */
    function マイページ、ブログの新規登録画面を開ける()
    {
        $this->login();
        $this->get('mypage/blogs/create')
            ->assertOk();
    }

    /** @test store */
    function マイページ、ブログを新規登録できる、公開の場合()
    {
        $this->login();

        $validData = Blog::factory()->validData();

        $this->post('mypage/blogs/create', $validData)
            ->assertRedirect('mypage/blogs/edit/1');

        $this->get('mypage/blogs/edit/1')->assertSee('新規登録しました');

        $this->assertDatabaseHas('blogs', $validData);
    }

    /** @test store */
    function マイページ、ブログを新規登録できる、非公開の場合()
    {
        $this->login();

        $validData = Blog::factory()->validData();

        unset($validData['is_open']);

        $this->post('mypage/blogs/create', $validData)
            ->assertRedirect('mypage/blogs/edit/1');

        $validData['is_open'] = 0;

        $this->get('mypage/blogs/edit/1')->assertSee('新規登録しました');

        $this->assertDatabaseHas('blogs', $validData);
    }

    /** @test store */
    function マイページ、ブログの登録時の入力チェック()
    {
        app()->setlocale('testing');

        $url = 'mypage/blogs/create';

        $this->login();

        $this->post($url, ['title' => ''])->assertInvalid(['title' => 'required']);
        $this->post($url, ['title' => str_repeat('a', 256)])->assertInvalid(['title' => 'max']);
        $this->post($url, ['title' => str_repeat('a', 255)])->assertValid(['title' => 'max']);
        $this->post($url, ['body' => ''])->assertInValid(['body' => 'required']);

        $this->from($url)->post($url, [])
            ->assertRedirect($url);
    }

    /** @test edit */
    function 他人様のブログの編集画面は開けない()
    {
        $blog = Blog::factory()->create();
        $this->login();
        $this->get('mypage/blogs/edit/'.$blog->id)
            ->assertForbidden();
    }

    /** @test update */
    function 他人様のブログは更新できない()
    {
        // 送信するデータを作る
        $validData = [
            'title' => '新タイトル',
            'body' => '新本文',
            'status' => 1,
        ];

        $blog = Blog::factory()->create();

        $this->login();

        $this->post('mypage/blogs/edit/'.$blog->id, $validData)
            ->assertForbidden();

        $this->assertCount(1, Blog::all());
        $this->assertEquals($blog->title, Blog::first()->title);
    }

    /** @test destroy */
    function 他人様のブログは削除できない()
    {
        $blog = Blog::factory()->create();

        $this->login();

        $this->delete('mypage/blogs/delete/'.$blog->id)
            ->assertForbidden();

        $this->assertCount(1, Blog::all());
    }

    /** @test edit */
    function 自分のブログの編集画面は開ける()
    {
        $blog = Blog::factory()->create();

        $this->login($blog->user);

        $this->get('mypage/blogs/edit/'.$blog->id)
            ->assertOk();
    }

    /** @test update */
    function 自分のブログは更新できる()
    {
        $validData = [
            'title' => '新タイトル',
            'body' => '新本文',
            'is_open' => 1,
        ];

        $blog = Blog::factory()->create();

        $this->login($blog->user);

        $this->post('mypage/blogs/edit/'.$blog->id, $validData)
            ->assertRedirect('mypage/blogs/edit/'.$blog->id);

        $this->get('mypage/blogs/edit/' . $blog->id)
            ->assertSee('ブログを更新しました。');

        $this->assertDatabaseHas('blogs', $validData);
        $this->assertCount(1, Blog::all());
    }

    /** @test destroy */
    function 自分のブログは削除でき、コメントも削除される()
    {
        $comment = Comment::factory()->create();

        $this->login($comment->blog->user);

        $this->delete('mypage/blogs/delete/'.$comment->blog->id)
            ->assertRedirect('mypage/blogs');

        $this->assertDatabaseMissing('blogs', ['id' => $comment->blog->id]);
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
