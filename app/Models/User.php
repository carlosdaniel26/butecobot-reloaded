<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'discord_id',
        'discord_username',
        'discord_global_name',
        'discord_avatar',
        'discord_joined_at',
        'received_initial_coins',
        'created_at',
        'updated_at',
    ];

    public function bets(): HasMany
    {
        return $this->hasMany(EventBet::class, 'user_id');
    }
}