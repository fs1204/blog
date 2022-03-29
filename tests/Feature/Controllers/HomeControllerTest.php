<?php

namespace Tests\Feature\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\HomeController
 */

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test index */
    function ブログのトップページを開く()
    {
        $blog1 = Blog::factory()->hasComments(1)->create();
        $blog2 = Blog::factory()->hasComments(3)->create();
        $blog3 = Blog::factory()->hasComments(2)->create();

        $this->get('/')
        ->assertOk()
        ->assertSee($blog1->title)
        ->assertSee($blog2->title)
        ->assertSee($blog3->title)
        ->assertSee($blog1->user->name)
        ->assertSee($blog2->user->name)
        ->assertSee($blog3->user->name)
        ->assertSee("（1件のコメント）")
        ->assertSee("（3件のコメント）")
        ->assertSee("（2件のコメント）")
        ->assertSeeInOrder([$blog2->title, $blog3->title, $blog1->title]);
    }

    /** @test index */
    function ブログの一覧、非公開のブログは表示されない()
    {
        Blog::factory()->closed()->create([
            'title' => 'ブログA',
        ]);

        Blog::factory()->create([
            'title' => 'ブログB',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('ブログA')
            ->assertSee('ブログB');
    }

    /** @test show */
    function ブログの詳細画面が表示できて、コメントが新しい順に表示される()
    {
        $blog = Blog::factory()->create();

        Comment::factory()->create([
            'created_at' => now()->sub('2 days'),
            'name' => 'あああ',
            'blog_id' => $blog->id,
        ]);
        Comment::factory()->create([
            'created_at' => now()->sub('3 days'),
            'name' => 'いいい',
            'blog_id' => $blog->id,
        ]);
        Comment::factory()->create([
            'created_at' => now()->sub('1 days'),
            'name' => 'ううう',
            'blog_id' => $blog->id,
        ]);

        $this->get('blogs/'.$blog->id)
            ->assertOk()
            ->assertSee($blog->title)
            ->assertSee($blog->body)
            ->assertSee($blog->user->name)
            ->assertSeeInOrder(['ううう', 'あああ', 'いいい']);
    }

    /** @test show */
    function ブログで非公開のものは、詳細画面は表示できない()
    {
        $blog = Blog::factory()->closed()->create([
            'title' => 'ブログA',
        ]);
        $this->get('blogs/'.$blog->id)
            ->assertForbidden();
    }
}
