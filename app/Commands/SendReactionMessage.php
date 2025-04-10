<?php

namespace App\Commands;

use App\Enums\ColorMessageEnum;
use Discord\Builders\MessageBuilder;
use Laracord\Commands\Command;

class SendReactionMessage extends Command
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'color';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'The Send Reaction Message command.';

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

        $roles = $guild->roles->filter(function ($role) {
            return preg_match('/\d/', $role->name);
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
                ->message(ColorMessageEnum::TEMPLATE->value)
                ->title('ㅤㅤㅤㅤ')
                ->select($roles, placeholder: ColorMessageEnum::PICK_A_COLOR->value, route: 'roles')
                ->send($targetChannel);

            $message->channel->sendMessage(ColorMessageEnum::SUCCESS->value);
        }
    }

    /**
     * The command interaction routes.
     */
    public function interactions(): array
    {
        return [
            'roles' => function (\Discord\Parts\Interactions\Interaction $interaction) {
                $roleId = $interaction->data->values[0];
                $member = $interaction->member;

                $rolesToRemove = $member->roles->filter(function ($role) {
                    return preg_match('/\d/', $role->name);
                });

                foreach ($rolesToRemove as $role) {
                    $member->removeRole($role->id);
                }

                $member->addRole($roleId);

                $interaction->respondWithMessage(MessageBuilder::new()->setContent(ColorMessageEnum::SUCCESS_UPDATED->value), ephemeral: true);
            },
        ];
    }
}
