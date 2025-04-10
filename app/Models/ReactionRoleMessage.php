<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReactionRoleMessage extends Model
{
    protected $fillable = [
        'message_id',
        'channel_id',
        'emoji_role_map',
    ];
}
