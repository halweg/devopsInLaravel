<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class UpsertPostTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_new_post()
    {
        $user = User::factory()->create();

        $data = Post::factory()->published()->make([
            'title' => 'Test post',
            'headline' => 'Test headline',
            'content' => 'Test content',
        ])->toArray();

        $this->actingAs($user);

        $post = $this->postJson(route('posts.store'), $data)
            ->assertStatus(Response::HTTP_CREATED)
            ->json('data');

        $this->assertEquals('Test post', $post['title']);
        $this->assertEquals('Test headline', $post['headline']);
        $this->assertEquals('Test content', $post['content']);
    }

    /** @test */
    public function it_should_update_a_post()
    {
        $user = User::factory()->create();

        $post = Post::factory()->published()->create([
            'title' => 'Test post',
            'headline' => 'Test headline',
            'content' => 'Test content',
        ]);

        $this->actingAs($user);

        $this->putJson(
                route('posts.update', ['post' => $post]),
                [
                    'title' => 'Updated post title',
                    'headline' => 'Test headline',
                    'content' => 'Updated content'
                ]
            )
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $post = $this->getJson(route('posts.show', ['post' => $post]))->json('data');

        $this->assertEquals('Updated post title', $post['title']);
        $this->assertEquals('Test headline', $post['headline']);
        $this->assertEquals('Updated content', $post['content']);
    }
}
