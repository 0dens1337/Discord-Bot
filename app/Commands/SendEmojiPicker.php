<?php

namespace App\Commands;

use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;
use Laracord\Commands\Command;

class SendEmojiPicker extends Command
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'emoji';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'The Send Emoji Picker command.';

    /**
     * Determines whether the command requires admin permissions.
     *
     * @var bool
     */
    protected $admin = false;

    /**
     * Determines whether the command should be displayed in the commands list.
     *
     * @var bool
     */
    protected $hidden = true;

    /**
     * Handle the command.
     *
     * @param  \Discord\Parts\Channel\Message  $message
     * @param  array  $args
     * @return void
     */
    public function handle($message, $args)
    {
        $guild = $message->guild;
        $member = $guild->members->get('id', $message->author->id);

        $requiredRole = config('laracord.role.admin');

        if (! $member->roles->has($requiredRole)) return;

        $roles = $guild->roles->filter(function ($role){
            return $role->icon != null;
        })->map(function ($role) {
            return [
                'label' => (string) $role->name,
                'value' => (string) $role->id,
            ];
        })->toArray();

        $targetChannelId = config('laracord.messages.role_channel_id');
        $targetChannel = $guild->channels->get('id', $targetChannelId);

        if ($targetChannel) {
            $this
                ->message('meow')
                ->title('sosal?')
                ->select($roles, route: 'emoji', placeholder: 'Можешь выбрать себе емодзи в ник')
                ->send($targetChannel);
        }

        $message->channel->sendMessage('Команда с эмодзи успешно выполнена!');
    }

    /**
     * The command interaction routes.
     */
    public function interactions(): array
    {
        return [
            'emoji' => function (Interaction $interaction) {
                $rolesId = $interaction->data->values[0];
                $member = $interaction->member;

                $rolesToRemove = $member->roles->filter(function ($role) {
                    return $role->icon != null;
                });

                foreach ($rolesToRemove as $role) {
                    $member->removeRole($role->id);
                }

                $member->addRole($rolesId);

                $interaction->respondWithMessage(MessageBuilder::new()->setContent('Эмодзи успешно обновлен!'), ephemeral: true);
            }
        ];
    }
}
