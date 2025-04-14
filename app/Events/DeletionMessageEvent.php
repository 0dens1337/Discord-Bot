<?php

namespace App\Events;

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Parts\Embed\Embed;
use Discord\WebSockets\Event as Events;
use Laracord\Events\Event;

class DeletionMessageEvent extends Event
{
    /**
     * The event handler.
     *
     * @var string
     */
    protected $handler = Events::MESSAGE_DELETE;

    /**
     * Handle the event.
     */
    public function handle(object $message, Discord $discord)
    {
        $channel = $discord->getChannel(config('laracord.messages.welcome_channel_id'));

        $messageId = $message->id;
        $channelId = $message->channel_id;

        $messageBuilder = (new MessageBuilder())
            ->addEmbed(
                (new Embed($discord))
                    ->setColor('FF0000')
                    ->setTitle('Лог действия')
                    ->setDescription("Сообщение с ID {$messageId}\nБыло удалено в канале {$channelId}.")
            );

        $channel->sendMessage($messageBuilder);
    }
}
