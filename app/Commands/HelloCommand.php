<?php

namespace App\Commands;

use Discord\Helpers\Collection;
use Discord\Parts\Interactions\Interaction;
use Laracord\Commands\Command;

class HelloCommand extends Command
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'hello';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'The Hello Command command.';

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
    protected $hidden = false;

    /**
     * Handle the command.
     *
     * @param  \Discord\Parts\Channel\Message  $message
     * @param  array  $args
     * @return void
     */
    public function handle($message, $args)
    {

    }
}
