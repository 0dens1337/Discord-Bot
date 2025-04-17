<?php

namespace App\Commands;

use Laracord\Commands\Command;

class EasterEggCommand extends Command
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'easterEgg';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'The Easter Egg Command command.';

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
        $easterEggRole = config('laracord.role.easter_egg');

        $member->addRole($easterEggRole);

        $message->reply('Поздравляем! Вы нашли пасхалку и получили специальную роль! 🎉');
        $message->delete();
    }
}
