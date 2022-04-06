<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login()
    {
        $this->seed(UsersTableSeeder::class);
        $response = $this->post("/login", [
            "login" => "test",
            "password" => "test",
            "remember" => false
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_login_with_email()
    {
        $this->seed(UsersTableSeeder::class);
        $response = $this->post("/login", [
            "login" => "test@test.com",
            "password" => "test",
            "remember" => false
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_login_with_invalid_password()
    {
        $this->post("/login", [
            "login" => "test",
            "password" => "wrongpassword",
            "remember" => false
        ]);
        $this->assertGuest();
    }

    public function test_register()
    {
        $response = $this->post('/register', [
            'name' => 'RegisterTestUser',
            'email' => 'registertest@test.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);
        $this->assertAuthenticated();
        $response->assertRedirect('/verify-email');
    }

    public function test_email_verify()
    {
        $this->seed(UsersTableSeeder::class);
        $user = User::where('email_verified_at', "=", null)->first();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');
    }

    public function test_email_verify_with_invalid_hash()
    {
        $this->seed(UsersTableSeeder::class);
        $user = User::where('email_verified_at', "=", null)->first();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_reset_password_request()
    {
        $this->seed(UsersTableSeeder::class);
        $user = User::where('name', "=", 'test')->first();

        Notification::fake();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_reset_password_page_load()
    {
        $this->seed(UsersTableSeeder::class);
        $user = User::where('name', "=", 'test')->first();

        Notification::fake();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
            $response = $this->get('/reset-password/' . $notification->token);
            $response->assertStatus(200);
            return true;
        });
    }

    public function test_reset_password_with_valid_token()
    {
        $this->seed(UsersTableSeeder::class);
        $user = User::where('name', "=", 'test')->first();

        Notification::fake();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'asdASD123',
                'password_confirmation' => 'asdASD123',
            ]);
            $response->assertSessionHasNoErrors();
            return true;
        });
    }

    public function test_reset_password_with_wrong_password()
    {
        $this->seed(UsersTableSeeder::class);
        $user = User::where('name', "=", 'test')->first();

        Notification::fake();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
            $response = $this->post('/reset-password', [
                'token' => $notification->token,
                'email' => $user->email,
                'password' => 'asd',
                'password_confirmation' => 'asd',
            ]);
            $response->assertSessionHasErrors();
            return true;
        });
    }

    public function test_password_with_right_password()
    {
        $this->seed(UsersTableSeeder::class);
        $user = User::where('name', "=", 'test')->first();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'test',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_password_with_wrong_password()
    {
        $this->seed(UsersTableSeeder::class);
        $user = User::where('name', "=", 'test')->first();

        $response = $this->actingAs($user)->post('/confirm-password', [
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
