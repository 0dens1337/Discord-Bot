<?php

namespace App\Events;

use App\Enums\EmbedColorsEnum;
use App\Enums\LogMessagesEnum;
use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Embed\Embed;
use Discord\Parts\WebSockets\VoiceStateUpdate;
use Discord\WebSockets\Event as Events;
use Laracord\Events\Event;

class VoiceLogEvent extends Event
{
    /**
     * The event handler.
     *
     * @var string
     */
    protected $handler = Events::VOICE_STATE_UPDATE;

    /**
     * Handle the event.
     */
    public function handle(VoiceStateUpdate $state, Discord $discord, ?VoiceStateUpdate $oldState)
    {
        if ($state->channel_id === ($oldState?->channel_id ?? null)) {
            return;
        }

        $channel = config('laracord.messages.welcome_channel_id');
        $logChannel = $discord->getChannel($channel);

        $actionType = $state->channel_id ? 'joined' : 'left';
        $formatedActionType = $this->formatedAction($actionType);
        $user = $state->user->username ?? 'unknown';
        $channelName = $state->channel_id
            ? $discord->getChannel($state->channel_id)->name ?? 'unknown channel'
            : ($oldState->channel_id
                ? $discord->getChannel($oldState->channel_id)->name ?? 'unknown channel'
                : 'unknown channel');

        $messageBuilder = (new MessageBuilder())
            ->addEmbed(
                (new Embed($discord))
                    ->setColor($actionType === 'joined' ? EmbedColorsEnum::GREEN_COLOR->value : EmbedColorsEnum::RED_COLOR->value)
                    ->setTitle(LogMessagesEnum::VOICE_MESSAGE->value)
                    ->setDescription("Пользователь: {$user} {$formatedActionType} {$channelName}.")
            );

        $logChannel->sendMessage($messageBuilder);
    }

    protected function formatedAction($actionType): string
    {
        return match ($actionType)
        {
            'joined' => 'вошел в канал',
            'left' => 'вышел из канала',
            default => 'unknown action'
        };
    }
}
