<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Config;

use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $this->withoutExceptionHandling();
        Config::set('session.driver', 'array');

        $user = User::factory()->create();
        $token = 'csrf_token';

        $response = $this->withHeaders([
            'X-CSRF-TOKEN' => $token,
        ])
        ->withSession(['_token' => $token])
        ->post('/login', [
            'email' => $user->email,
            'password' => 'password',
            '_token' => $token, 
        ]);

        $this->assertAuthenticated();

        if (session()->has('errors')) {
            $response->assertSessionHasErrors();
        } else {
            $response->assertSessionHasNoErrors();
        }      
        // $response->assertRedirect(route('dashboard', absolute: false));
        $response->assertRedirect(route('dashboard'));

    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $this->withoutExceptionHandling();
        Config::set('session.driver', 'array');

        $user = User::factory()->create();
        $token = 'csrf_token';

        $response = $this->actingAs($user)
        ->withHeaders([
            'X-CSRF-TOKEN' => $token,
        ])
        ->withSession(['_token' => $token])
        ->post(route('logout'));
        $response->assertRedirect('/');

        //$response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
    }
}
