<?php

namespace Tests\Unit;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @see \app\Models\User
 */
class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test blogs */
    function blogsリレーションを返す()
    {
        $user = User::factory()->create();
        Blog::factory()->create(['user_id' => $user]);
        $this->assertInstanceOf(Blog::class, $user->blogs()->first());
    }
}
