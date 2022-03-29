<?php

namespace Tests\Unit\Models;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \app\Models\Blog
 */
class BlogTest extends TestCase
{
    use RefreshDatabase;

    /** @test user */
    function userリレーションを返す()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(User::class, $blog->user);
    }

    /** @test comments */
    function commentsリレーションを返す()
    {
        $blog = Blog::factory()->create();
        $this->assertInstanceOf(Collection::class, $blog->comments);
    }
}
