<?php

namespace App\Events;

use App\Enums\ActionTypeEnum;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
use Discord\WebSockets\Event as Events;
use Laracord\Events\Event;

class LogEvent extends Event
{
    /**
     * The event handler.
     *
     * @var string
     */
    protected $handler = Events::GUILD_AUDIT_LOG_ENTRY_CREATE;

    /**
     * Handle the event.
     */
    public function handle($auditLogEntry, $discord, $voiceLogEntry)
    {
        $logChannelId = config('laracord.messages.welcome_channel_id');
        $logChannel = $discord->getChannel($logChannelId);

        $actionType = $auditLogEntry->action_type ?? 'unknown';
        $actionTypeFormated = $this->formatedActionType($actionType);
        $userId = $auditLogEntry->user->username ?? 'unknown';
        $targetId = $auditLogEntry->target_id ?? 'я не понимаю зачем это нужно';

        $messageBuilder = (new MessageBuilder())
            ->addEmbed(
                (new Embed($discord))
                    ->setColor('000000')
                    ->setTitle('Лог действия')
                    ->setDescription("Действие: {$actionTypeFormated}\nПользователь: {$userId}\nЦель: {$targetId}")
            );

        $logChannel->sendMessage($messageBuilder);
    }

    public function formatedActionType($actionType): string
    {
        $action = ActionTypeEnum::tryFrom($actionType);

        return $action ? $action->description() : (string) $actionType;
    }

}
