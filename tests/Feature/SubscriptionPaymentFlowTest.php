<?php

namespace Tests\Feature;

use App\Models\PaymentMethod;
use App\Models\SubscriptionRequest;
use App\Models\SupportContact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * The paid-subscription round trip: the Super Admin publishes an account, the
 * client pays into it and files proof, and the approval flips the plan on.
 */
class SubscriptionPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'super_admin', 'status' => 'active']);
    }

    private function client(): User
    {
        return User::factory()->create([
            'role' => 'client',
            'status' => 'active',
            'subscription_status' => User::SUB_NONE,
            'phone' => '03001234567',
        ]);
    }

    public function test_super_admin_can_add_a_payment_method(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->post(route('admin.payment-methods.store'), [
                'type' => 'jazzcash',
                'label' => 'JazzCash — Main',
                'account_name' => 'Muhammad Uzair',
                'account_number' => '0300-1234567',
                'qr_image' => UploadedFile::fake()->image('qr.png'),
            ])
            ->assertRedirect();

        $method = PaymentMethod::first();
        $this->assertNotNull($method);
        $this->assertSame('JazzCash — Main', $method->label);
        $this->assertTrue($method->is_active);
        $this->assertSame(0, $method->sort_order);
        Storage::disk('public')->assertExists($method->qr_image_path);
    }

    public function test_super_admin_can_add_a_support_contact_and_it_builds_a_link(): void
    {
        $this->actingAs($this->admin())
            ->post(route('admin.support-contacts.store'), [
                'channel' => 'whatsapp',
                'label' => 'WhatsApp Support',
                'value' => '923001234567',
            ])
            ->assertRedirect();

        $contact = SupportContact::first();
        $this->assertSame('https://wa.me/923001234567', $contact->url());

        $insta = SupportContact::create([
            'channel' => 'instagram',
            'label' => 'Instagram',
            'value' => '@birthdaycards',
        ]);
        $this->assertSame('https://instagram.com/birthdaycards', $insta->url());
    }

    public function test_client_must_attach_proof_to_request_a_plan(): void
    {
        $client = $this->client();
        PaymentMethod::create([
            'type' => 'easypaisa', 'label' => 'EasyPaisa',
            'account_name' => 'Uzair', 'account_number' => '03001112222',
        ]);

        $this->actingAs($client)
            ->post(route('client.subscription.request'), ['plan_amount' => 399])
            ->assertSessionHasErrors(['payment_method_id', 'sender_name', 'sender_number', 'payment_screenshot']);

        $this->assertSame(0, SubscriptionRequest::count());
    }

    public function test_client_cannot_pay_into_a_hidden_account(): void
    {
        Storage::fake('public');
        $hidden = PaymentMethod::create([
            'type' => 'bank', 'label' => 'Old Account',
            'account_name' => 'Uzair', 'account_number' => 'PK00XXXX',
            'is_active' => false,
        ]);

        $this->actingAs($this->client())
            ->post(route('client.subscription.request'), [
                'plan_amount' => 199,
                'payment_method_id' => $hidden->id,
                'sender_name' => 'Ali',
                'sender_number' => '03009998888',
                'payment_screenshot' => UploadedFile::fake()->image('proof.jpg'),
            ])
            ->assertSessionHasErrors('payment_method_id');
    }

    public function test_full_pay_then_approve_activates_the_plan(): void
    {
        Storage::fake('public');

        $admin = $this->admin();
        $client = $this->client();
        $method = PaymentMethod::create([
            'type' => 'jazzcash', 'label' => 'JazzCash',
            'account_name' => 'Uzair', 'account_number' => '03001234567',
        ]);

        // ── Client pays and files proof ──
        $this->actingAs($client)
            ->post(route('client.subscription.request'), [
                'plan_amount' => 399,
                'payment_method_id' => $method->id,
                'sender_name' => 'Ali Raza',
                'sender_number' => '03009998888',
                'transaction_id' => 'TID-55512',
                'client_note' => 'Sent at 4pm',
                'payment_screenshot' => UploadedFile::fake()->image('proof.jpg'),
            ])
            ->assertSessionHasNoErrors();

        $request = SubscriptionRequest::first();
        $this->assertNotNull($request);
        $this->assertSame('Ali Raza', $request->sender_name);
        $this->assertSame('TID-55512', $request->transaction_id);
        $this->assertSame($method->id, $request->payment_method_id);
        $this->assertTrue($request->isPending());
        Storage::disk('public')->assertExists($request->payment_screenshot_path);
        $this->assertStringContainsString('storage/', $request->screenshotUrl());

        $client->refresh();
        $this->assertSame(User::SUB_PENDING, $client->subscription_status);

        // ── Admin sees the proof on the approval queue ──
        $this->actingAs($admin)
            ->get(route('admin.subscriptions.index'))
            ->assertOk()
            ->assertSee('Ali Raza')
            ->assertSee('03009998888')
            ->assertSee('TID-55512');

        // ── Admin approves → plan is live ──
        $this->actingAs($admin)
            ->patch(route('admin.subscriptions.approve', $request->id))
            ->assertRedirect();

        $client->refresh();
        $this->assertTrue($client->hasActiveSubscription());
        $this->assertSame(399, (int) $client->plan_amount);
        $this->assertSame(3, $client->cardLimit());
        $this->assertSame(SubscriptionRequest::APPROVED, $request->fresh()->status);
    }

    public function test_client_sees_the_accounts_and_support_channels_on_the_hub(): void
    {
        PaymentMethod::create([
            'type' => 'bank', 'label' => 'Meezan Bank',
            'account_name' => 'Uzair', 'account_number' => 'PK36MEZN0001',
            'bank_name' => 'Meezan Bank',
        ]);
        SupportContact::create([
            'channel' => 'whatsapp', 'label' => 'WhatsApp Support', 'value' => '923001234567',
        ]);

        $this->actingAs($this->client())
            ->get(route('client.cards'))
            ->assertOk()
            ->assertSee('PK36MEZN0001')
            ->assertSee('Meezan Bank')
            ->assertSee('https://wa.me/923001234567');
    }

    public function test_contact_page_lists_every_published_channel(): void
    {
        SupportContact::create([
            'channel' => 'whatsapp', 'label' => 'WhatsApp Support',
            'value' => '923001234567', 'note' => 'Replies in 2 hours',
        ]);
        SupportContact::create([
            'channel' => 'instagram', 'label' => 'Instagram DM', 'value' => '@birthdaycards',
        ]);
        SupportContact::create([
            'channel' => 'facebook', 'label' => 'Hidden Page',
            'value' => 'oldpage', 'is_active' => false,
        ]);
        PaymentMethod::create([
            'type' => 'jazzcash', 'label' => 'JazzCash',
            'account_name' => 'Uzair', 'account_number' => '03001234567',
        ]);

        $this->actingAs($this->client())
            ->get(route('client.contact'))
            ->assertOk()
            ->assertSee('Contact Support')
            ->assertSee('WhatsApp Support')
            ->assertSee('Replies in 2 hours')
            // The number itself is printed, not just the label.
            ->assertSee('923001234567')
            ->assertSee('https://wa.me/923001234567')
            ->assertSee('@birthdaycards')
            ->assertSee('https://instagram.com/birthdaycards')
            // Payment accounts belong on the plan screen, not here.
            ->assertDontSee('03001234567')
            // A hidden channel must not leak onto the page.
            ->assertDontSee('Hidden Page');
    }

    public function test_contact_page_requires_login(): void
    {
        $this->get(route('client.contact'))->assertRedirect();
    }

    public function test_landing_page_links_to_contact_but_does_not_inline_it(): void
    {
        SupportContact::create([
            'channel' => 'whatsapp', 'label' => 'WhatsApp Support', 'value' => '923001234567',
        ]);

        $this->get('/')
            ->assertOk()
            // Contact is its own page now — only the nav/footer link and the
            // social icons live on the landing page.
            ->assertSee(route('contact'), false)
            ->assertSee('l-nav__social', false)
            ->assertSee('https://wa.me/923001234567')
            ->assertDontSee('l-contact__grid', false);
    }

    public function test_public_contact_page_lists_the_channels_without_login(): void
    {
        SupportContact::create([
            'channel' => 'whatsapp', 'label' => 'WhatsApp Support',
            'value' => '923001234567', 'note' => 'Replies in 2 hours',
        ]);
        SupportContact::create([
            'channel' => 'instagram', 'label' => 'Instagram DM', 'value' => '@birthdaycards',
        ]);
        SupportContact::create([
            'channel' => 'facebook', 'label' => 'Hidden Page',
            'value' => 'oldpage', 'is_active' => false,
        ]);

        // No actingAs — this page is for visitors who have not signed up.
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('l-contact__grid', false)
            ->assertSee('WhatsApp Support')
            ->assertSee('https://wa.me/923001234567')
            ->assertSee('Instagram DM')
            ->assertSee('https://instagram.com/birthdaycards')
            ->assertDontSee('Hidden Page');
    }

    public function test_public_contact_page_stays_up_when_nothing_is_published(): void
    {
        $this->get(route('contact'))
            ->assertOk()
            ->assertSee('Common')
            ->assertDontSee('l-contact__grid', false);
    }

    public function test_admin_payment_methods_page_loads(): void
    {
        $this->actingAs($this->admin())
            ->get(route('admin.payment-methods.index'))
            ->assertOk()
            ->assertSee('Payment Methods')
            ->assertSee('Chat support contacts');
    }
}
