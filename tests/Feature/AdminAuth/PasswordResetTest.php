<?php

namespace Tests\Feature\AdminAuth;

use App\Models\Admin;
//use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\AdminResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_reset_password_link_can_be_requested(): void
    {
        Notification::fake();

        $admin = Admin::factory()->create();

        $this->post('/admin/forgot-password', ['email' => $admin->email]);

        Notification::assertSentTo($admin, AdminResetPassword::class);
    }

    public function test_password_can_be_reset_with_valid_token(): void
    {
        Notification::fake();

        $admin = Admin::factory()->create();

        $this->post('/admin/forgot-password', ['email' => $admin->email]);

        Notification::assertSentTo($admin, AdminResetPassword::class, function (object $notification) use ($admin) {
            $response = $this->post('/admin/reset-password', [
                'token' => $notification->token,
                'email' => $admin->email,
                'password' => 'password',
                'password_confirmation' => 'password',
            ]);

            $response->assertSessionHasNoErrors();

            return true;
        });
    }
}
