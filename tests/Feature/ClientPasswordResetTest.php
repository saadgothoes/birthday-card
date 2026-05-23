<?php

namespace Tests\Feature;

use App\Mail\ClientPasswordReset;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class ClientPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_request_a_password_reset_link(): void
    {
        Mail::fake();

        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
            'password_changed' => false,
        ]);

        $response = $this->post(route('client.forgot-password.send'), [
            'email' => $client->email,
        ]);

        $response->assertSessionHas('status', 'Password reset link sent to your email!');
        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => $client->email,
        ]);

        Mail::assertSent(ClientPasswordReset::class, function (ClientPasswordReset $mail) use ($client) {
            return $mail->hasTo($client->email) && str_contains($mail->resetUrl, $client->email);
        });
    }

    public function test_client_can_reset_password_from_email_link(): void
    {
        $client = User::factory()->create([
            'role' => 'client',
            'status' => 'active',
            'password' => 'OldPass123',
            'plain_password' => 'OldPass123',
            'password_changed' => false,
        ]);

        $token = Password::createToken($client);

        $response = $this->post(route('client.password.update'), [
            'token' => $token,
            'email' => $client->email,
            'password' => 'NewPass123',
            'password_confirmation' => 'NewPass123',
        ]);

        $response->assertRedirect(route('client.login'));
        $response->assertSessionHas('status', 'Password reset successfully!');

        $client->refresh();

        $this->assertTrue(Hash::check('NewPass123', $client->password));
        $this->assertTrue($client->password_changed);
        $this->assertNull($client->plain_password);
        $this->assertCredentials([
            'email' => $client->email,
            'password' => 'NewPass123',
        ]);
    }
}
