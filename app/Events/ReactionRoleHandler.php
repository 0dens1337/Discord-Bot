<?php

namespace App\Events;

use App\Models\ReactionRoleMessage;
use Discord\Discord;
use Discord\Parts\WebSockets\MessageReaction;
use Discord\WebSockets\Event as Events;
use Laracord\Events\Event;

class ReactionRoleHandler extends Event
{
    /**
     * The event handler.
     *
     * @var string
     */
    protected $handler = Events::MESSAGE_REACTION_ADD;

    /**
     * Handle the event.
     */
    public function handle(MessageReaction $reaction, Discord $discord)
    {
        $targetReaction = ReactionRoleMessage::query()->firstWhere('message_id', $reaction->message_id);
        if (! $targetReaction) return;

        $guild = $reaction->guild;
        $member = $guild->members->get('id', $reaction->user_id);

        $emojiMap = json_decode($targetReaction->emoji_role_map, true);
        $roleId = $emojiMap[$reaction->emoji->name] ?? null;
        $role = $guild->roles->get('id', $roleId);

        if (! $role) return;

        $role = $guild->roles->get('id', $roleId);
        if (! $role) return;

        $member->addRole($role);
    }
}
