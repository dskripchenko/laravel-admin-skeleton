<?php

namespace Tests\Feature;

use App\Enums\PostStatus;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function login(): void
    {
        $this->postJson('/api/admin/auth/login', [
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),
            'password' => env('ADMIN_PASSWORD', 'password'),
        ])->assertOk();
    }

    public function test_the_seeded_administrator_can_sign_in(): void
    {
        $this->login();

        $this->getJson('/api/admin/system/me')
            ->assertOk()
            ->assertJsonPath('payload.email', env('ADMIN_EMAIL', 'admin@example.com'));
    }

    public function test_the_shell_is_served(): void
    {
        $this->get('/admin/login')->assertOk()->assertSee('admin-app', false);
    }

    public function test_the_menu_lists_the_example_resource_and_the_starter_pack(): void
    {
        $this->login();

        $menu = json_encode($this->getJson('/api/admin/system/menu')->assertOk()->json());

        $this->assertStringContainsString('posts', $menu);
        $this->assertStringContainsString('system-users', $menu);
    }

    public function test_the_example_resource_searches_posts(): void
    {
        $this->login();
        $post = Post::query()->firstOrFail();

        $response = $this->postJson('/api/admin/posts/search', ['q' => $post->title])->assertOk();

        $this->assertContains($post->id, array_column($response->json('payload.data'), 'id'));
    }

    public function test_the_example_resource_filters_posts_by_status(): void
    {
        $this->login();

        $response = $this->postJson('/api/admin/posts/search', [
            'filters' => ['status' => PostStatus::Draft->value],
            'per_page' => 100,
        ])->assertOk();

        $this->assertSame(
            Post::where('status', PostStatus::Draft)->count(),
            count($response->json('payload.data')),
        );
        $this->assertSame([PostStatus::Draft->value], array_values(array_unique(array_column($response->json('payload.data'), 'status'))));
    }

    public function test_the_overview_dashboard_loads(): void
    {
        $this->login();

        $this->getJson('/api/admin/dashboard/get?key=main')->assertOk();
    }
}
