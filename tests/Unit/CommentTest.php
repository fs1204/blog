<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \app\Models\Comment
 */
class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test blog */
    function blogリレーションを返す()
    {
        $comment = Comment::factory()->create();
        $this->assertInstanceOf(Blog::class, $comment->blog);
    }
}
