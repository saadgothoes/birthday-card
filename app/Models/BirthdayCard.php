<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'theme',
    'variant',
    'heading',
    'dob',
    'lock_code',
    'lock_hint',
    'recipient_name',
    'sender_name',
    'welcome_message',
    'profile_image_path',
    'gifts',
    'gift_screen_variant',
    'gift1_data',
    'gift2_data',
    'gift3_data',
    'current_step',
    'slug',
    'is_published',
])]
class BirthdayCard extends Model
{
    protected function casts(): array
    {
        return [
            'dob' => 'date',
            'gifts' => 'array',
            'gift1_data' => 'array',
            'gift2_data' => 'array',
            'gift3_data' => 'array',
            'is_published' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
