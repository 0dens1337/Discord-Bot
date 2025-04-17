<?php

namespace App\Events;

use App\Enums\RoleMessageEnum;
use Discord\Discord;
use Discord\Parts\User\Member;
use Discord\WebSockets\Event as Events;
use Laracord\Events\Event;

class NewComerJoined extends Event
{
    /**
     * The event handler.
     *
     * @var string
     */
    protected $handler = Events::GUILD_MEMBER_ADD;

    /**
     * Handle the event.
     */
    public function handle(Member $member, Discord $discord)
    {
        $channel = $discord->getChannel(config('laracord.messages.welcome_channel_id'));

        if ($channel) {
            $channel->sendMessage("Привет, <@{$member->user->id}>! Добро пожаловать на сервер! 🎉" . RoleMessageEnum::FOR_NEW_COMERS->value);
        } else {
            logger()->error('Welcome channel not found. Please check your configuration.');
        }
    }
}
