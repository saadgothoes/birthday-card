<?php

namespace Tests\Feature;

use App\Models\BirthdayCard;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * The card hub used to list the same card more than once: every card appeared
 * under Recent and again under Drafts/Completed, and the activity feed logged
 * both "Created" and "Edited" for a single card.
 */
class CardHubTest extends TestCase
{
    use RefreshDatabase;

    private function client(): User
    {
        return User::factory()->create([
            'role' => 'client',
            'status' => 'active',
            'subscription_status' => User::SUB_ACTIVE,
            'plan_amount' => 599,
            'card_limit' => 6,
        ]);
    }

    public function test_activity_feed_lists_each_card_once(): void
    {
        $user = $this->client();

        // Edited well after creation — the old code emitted Created *and*
        // Edited for exactly this shape.
        $draft = BirthdayCard::create([
            'user_id' => $user->id, 'title' => 'Alpha Draft',
            'theme' => 'girl', 'current_step' => 4, 'is_published' => false,
        ]);
        $draft->forceFill([
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subHour(),
        ])->save();

        $done = BirthdayCard::create([
            'user_id' => $user->id, 'title' => 'Gamma Done',
            'theme' => 'boy', 'current_step' => 10, 'is_published' => true, 'slug' => 'gamma',
        ]);
        $done->forceFill([
            'created_at' => now()->subDays(3),
            'updated_at' => now()->subMinutes(10),
        ])->save();

        $activity = $this->actingAs($user)->get(route('client.cards'))
            ->assertOk()
            ->viewData('activity');

        $perCard = $activity->groupBy(fn ($event) => $event['card']->id)->map->count();

        foreach ($perCard as $cardId => $count) {
            $this->assertSame(1, $count, "Card {$cardId} appears {$count} times in the activity feed.");
        }

        // The newest state is what gets reported.
        $texts = $activity->keyBy(fn ($e) => $e['card']->title)->map(fn ($e) => $e['text']);
        $this->assertSame('Edited', $texts['Alpha Draft']);
        $this->assertSame('QR generated for', $texts['Gamma Done']);
    }

    public function test_a_brand_new_card_reports_as_created(): void
    {
        $user = $this->client();
        BirthdayCard::create([
            'user_id' => $user->id, 'title' => 'Fresh',
            'theme' => 'girl', 'current_step' => 1, 'is_published' => false,
        ]);

        $activity = $this->actingAs($user)->get(route('client.cards'))->viewData('activity');

        $this->assertCount(1, $activity);
        $this->assertSame('Created', $activity[0]['text']);
    }

    public function test_only_one_card_list_is_visible_at_a_time(): void
    {
        $user = $this->client();
        BirthdayCard::create([
            'user_id' => $user->id, 'title' => 'Alpha Draft',
            'theme' => 'girl', 'current_step' => 3, 'is_published' => false,
        ]);
        BirthdayCard::create([
            'user_id' => $user->id, 'title' => 'Gamma Done',
            'theme' => 'boy', 'current_step' => 10, 'is_published' => true, 'slug' => 'gamma',
        ]);

        $html = $this->actingAs($user)->get(route('client.cards'))->assertOk()->getContent();

        // Recent is the open tab; the other two ship collapsed, so no card is
        // ever rendered twice on screen.
        $this->assertStringContainsString('data-tab-panel="recent"', $html);
        $this->assertMatchesRegularExpression('/data-tab-panel="drafts"[^>]*\shidden/', $html);
        $this->assertMatchesRegularExpression('/data-tab-panel="completed"[^>]*\shidden/', $html);
        $this->assertSame(1, substr_count($html, 'data-tab-panel="recent"'));
    }
}
