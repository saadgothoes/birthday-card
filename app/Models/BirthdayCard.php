<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'user_id',
    'theme',
    'variant',
    'dob',
    'lock_code',
    'lock_hint',
    'recipient_name',
    'sender_name',
    'welcome_message',
    'profile_image_path',
    'gifts',
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
            'is_published' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
