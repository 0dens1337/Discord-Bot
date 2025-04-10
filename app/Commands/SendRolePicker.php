<?php

namespace App\Commands;

use App\Enums\ColorMessageEnum;
use App\Enums\EmojiMessageEnum;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Interactions\Interaction;
use Laracord\Commands\Command;

class SendRolePicker extends Command
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'role';

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

        $rolesWithNumbers = $guild->roles->filter(function ($role) {
            return preg_match('/\d/', $role->name);
        })->map(function ($role) {
            return [
                'label' => (string) $role->name,
                'value' => (string) $role->id,
            ];
        })->toArray();

        $rolesWithIcons = $guild->roles->filter(function ($role) {
            return $role->icon != null;
        })->map(function ($role) {
            return [
                'label' => (string) $role->name,
                'value' => (string) $role->id,
            ];
        })->toArray();

        $this
            ->message(ColorMessageEnum::TEMPLATE->value)
            ->title('ㅤㅤㅤㅤ')
            ->image('')
            ->select($rolesWithNumbers, placeholder: ColorMessageEnum::PICK_A_COLOR->value, route: 'roles_with_numbers')
            ->select($rolesWithIcons, placeholder: EmojiMessageEnum::PICK_AN_EMOJI->value, route: 'roles_with_icons')
            ->send($message);
        }


        /**
     * The command interaction routes.
     */
    public function interactions(): array
    {
        return [
            'roles_with_numbers' => function (Interaction $interaction) {
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
            'roles_with_icons' => function (Interaction $interaction) {
                $roleId = $interaction->data->values[0];
                $member = $interaction->member;

                $rolesToRemove = $member->roles->filter(function ($role) {
                    return $role->icon != null;
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
