<?php

namespace Tests\Feature;

use App\Models\BirthdayCard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The four proposal designs, and the wiring that is per-design rather than
 * shared: which fields a design stores, which it refuses, and that the page a
 * recipient opens is the one the client picked.
 */
class ProposalDesignTest extends TestCase
{
    use RefreshDatabase;

    private function client(): User
    {
        return User::factory()->create([
            'role' => 'client',
            'subscription_status' => 'active',
        ]);
    }

    public function test_saves_the_new_per_design_fields(): void
    {
        $user = $this->client();

        $this->actingAs($user)
            ->post('/client/card/proposal/design', ['design' => 1, 'theme' => 3])
            ->assertOk();

        $this->actingAs($user)->post('/client/card/proposal/content', [
            'to_name' => 'Sara',
            'from_name' => 'Umair',
            'heading' => 'Us',
            'tap_label' => 'Tap to open',
            'chat_text' => "one\ntwo\nthree",
            'letter_text' => "a line\nanother line",
            'question' => 'Will you marry me?',
            'yes_label' => 'Yes',
            'no_label' => 'No',
            'yes_heading' => 'She said yes',
            'closing_line' => 'pinned.',
            'signed' => '— always yours',
        ])->assertOk();

        $card = BirthdayCard::where('user_id', $user->id)->firstOrFail();
        $this->assertSame('proposal', $card->occasion);
        $this->assertSame(1, $card->variant);
        $this->assertSame(3, $card->gift_screen_variant);
        $this->assertSame("one\ntwo\nthree", $card->gift1_data['chat_text']);
        // the letter the Yes opens is stored for every design
        $this->assertSame("a line\nanother line", $card->gift1_data['letter_text']);
        // a field no design reads must not be carried in the payload
        $this->assertArrayNotHasKey('countdown_seconds', $card->gift1_data);
        $this->assertArrayNotHasKey('caption_text', $card->gift1_data);
    }

    public function test_rejects_a_thread_longer_than_the_design_can_show(): void
    {
        $user = $this->client();
        $this->actingAs($user)->post('/client/card/proposal/design', ['design' => 1, 'theme' => 1]);

        $this->actingAs($user)->post('/client/card/proposal/content', [
            'chat_text' => "1\n2\n3\n4\n5\n6",
            'question' => 'Will you marry me?',
        ])->assertSessionHasErrors('chat_text');

        $this->actingAs($user)->post('/client/card/proposal/content', [
            'chat_text' => "1\n2",
            'letter_text' => "1\n2\n3\n4\n5\n6\n7",
            'question' => 'Will you marry me?',
        ])->assertSessionHasErrors('letter_text');
    }

    public function test_the_roll_keeps_its_three_photo_slots(): void
    {
        $spec = \App\Http\Controllers\Client\BirthdayCardController::proposalDesign(4);
        $this->assertSame(['photo_1', 'photo_2', 'photo_3', 'ring_photo'], $spec['photos']);
        $this->assertContains('caption_text', $spec['fields']);
    }

    public function test_every_design_renders_on_every_theme(): void
    {
        foreach ([1, 2, 3, 4] as $design) {
            foreach ([1, 2, 3, 4] as $theme) {
                $this->get("/proposal/design/{$design}/{$theme}")
                    ->assertOk()
                    ->assertSee('data-tease-yes', false);
            }
        }

        $this->get('/proposal/design/5/1')->assertNotFound();
        $this->get('/proposal/design/1/9')->assertNotFound();
    }

    public function test_every_design_opens_a_letter_after_the_yes(): void
    {
        foreach ([1, 2, 3, 4] as $design) {
            $spec = \App\Http\Controllers\Client\BirthdayCardController::proposalDesign($design);
            $this->assertContains('letter_text', $spec['fields'], "design {$design}");
            $this->assertNotEmpty($spec['defaults']['letter_text']);

            // the sheet is on the page, with the design's own entrance
            $this->get("/proposal/design/{$design}/1?preview_stage=yes")
                ->assertOk()
                ->assertSee('id="paOverlay"', false)
                ->assertSee('class="pa-line"', false);
        }
    }

    public function test_a_proposal_card_still_carries_its_music(): void
    {
        $user = $this->client();
        $card = BirthdayCard::create([
            'user_id' => $user->id,
            'occasion' => 'proposal',
            'variant' => 1,
            'gift_screen_variant' => 1,
            'slug' => 'temp-proposal-music',
            'is_published' => true,
            'qr_data' => ['theme' => 1],
            'current_step' => 10,
            'music_data' => ['source' => 'upload', 'path' => 'music/test.mp3'],
            'gift1_data' => ['design' => 1, 'theme' => 1, 'photos' => []],
        ]);

        // the shell is what carries the player; the page inside it is the design
        $this->get('/c/' . $card->slug)->assertOk()->assertSee('music/test.mp3', false);
    }

    public function test_the_published_page_is_the_design_the_client_chose(): void
    {
        $user = $this->client();
        $card = BirthdayCard::create([
            'user_id' => $user->id,
            'occasion' => 'proposal',
            'variant' => 3,
            'gift_screen_variant' => 2,
            'slug' => 'temp-proposal-slug',
            'is_published' => true,
            'qr_data' => ['theme' => 1],
            'current_step' => 10,
            'gift1_data' => [
                'design' => 3, 'theme' => 2,
                'to_name' => 'Sara', 'from_name' => 'Umair',
                'question' => 'Marry me?', 'photos' => [],
            ],
        ]);

        // the bare link serves the shell; the frame inside it is the page
        $this->get('/c/' . $card->slug)->assertOk();

        $res = $this->get('/c/' . $card->slug . '?frame=1');
        $res->assertOk();
        $res->assertSee('Marry me?', false);
        $res->assertSee('Seven stars joined', false);   // design 3's constellation
        // the inner pages of the other occasions do not exist for a proposal
        $this->get('/c/' . $card->slug . '/welcome')->assertNotFound();
    }
}
