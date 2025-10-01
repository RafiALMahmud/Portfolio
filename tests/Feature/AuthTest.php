<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationMail;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    public function test_user_can_register()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'present_address' => '123 Main St',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/email/verify');
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'user',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticated();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    public function test_user_can_update_account()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/account', [
            'name' => 'Updated Name',
            'phone' => '9876543210',
            'present_address' => '456 New St',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'phone' => '9876543210',
        ]);
    }

    public function test_user_can_change_password()
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);
        $this->actingAs($user);

        $response = $this->post('/account/password', [
            'current_password' => 'oldpassword',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect();
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_user_can_request_password_reset()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->post('/forgot-password', [
            'email' => 'test@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');
    }

    public function test_admin_cannot_reset_password()
    {
        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $response = $this->post('/forgot-password', [
            'email' => 'admin@example.com',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email']);
    }

    public function test_verification_email_is_sent_on_registration()
    {
        Mail::fake();

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'present_address' => '123 Main St',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $this->post('/register', $userData);

        Mail::assertSent(VerificationMail::class);
    }
}
