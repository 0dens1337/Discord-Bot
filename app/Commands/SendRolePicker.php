<?php

namespace App\Commands;

use App\Enums\EmbedColorsEnum;
use App\Enums\RoleMessageEnum;
use App\Enums\EmojiMessageEnum;
use Discord\Builders\MessageBuilder;
use Discord\Parts\Embed\Embed;
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

        $rolesWithNumbers = $this->getRolesByCondition($guild, function ($role) {
            return preg_match('/\d/', $role->name);
        }, 'strnatcmp');

        $rolesWithIcons = $this->getRolesByCondition($guild, function ($role) {
            return $role->icon_hash != null;
        }, 'strcasecmp');

        $this
            ->message()
            ->withEmbeds([
                (new MessageBuilder())
                    ->addEmbed(
                        (new Embed($this->discord()))
                            ->setColor(EmbedColorsEnum::DEFAULT_COLOR->value)
                            ->setImage(RoleMessageEnum::IMAGE->value)
                    ),
                (new MessageBuilder())
                    ->addEmbed((new Embed($this->discord()))
                        ->setColor(EmbedColorsEnum::DEFAULT_COLOR->value)
                        ->setTitle(EmojiMessageEnum::EMOJI_TEMPLATE->value)
                        ->setDescription(RoleMessageEnum::TEMPLATE->value)
                        ->setFooter(RoleMessageEnum::FOOTER->value)
                    )
            ])
            ->select($rolesWithNumbers, placeholder: RoleMessageEnum::PICK_A_COLOR->value, route: 'roles_with_numbers')
            ->select($rolesWithIcons, placeholder: EmojiMessageEnum::PICK_AN_EMOJI->value, route: 'roles_with_icons')
            ->send($message);

        $message->delete();
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

                $interaction->respondWithMessage(MessageBuilder::new()->setContent(RoleMessageEnum::SUCCESS_UPDATED->value), ephemeral: true);
            },
            'roles_with_icons' => function (Interaction $interaction) {
                $requiredRoleId = config('laracord.role.donater');
                $member = $interaction->member;

                if (! $member->roles->has($requiredRoleId)) {
                    $interaction->respondWithMessage(
                        MessageBuilder::new()->setContent(RoleMessageEnum::NO_RIGHTS->value),
                        ephemeral: true
                    );
                    return;
                }

                $roleId = $interaction->data->values[0];

                $rolesToRemove = $member->roles->filter(function ($role) {
                    return $role->icon != null;
                });

                foreach ($rolesToRemove as $role) {
                    $member->removeRole($role->id);
                }

                $member->addRole($roleId);

                $interaction->respondWithMessage(
                    MessageBuilder::new()->setContent(EmojiMessageEnum::SUCCESS_UPDATED->value),
                    ephemeral: true
                );
            },
        ];
    }

    private function getRolesByCondition($guild, callable $filterCondition, string $sortFunction): array
    {
        return $guild->roles->filter($filterCondition)
            ->sort(function ($a, $b) use ($sortFunction) {
                return $sortFunction($a->name, $b->name);
            })
            ->map(function ($role) {
                return [
                    'label' => (string) $role->name,
                    'value' => (string) $role->id,
                ];
            })->toArray();
    }
}
