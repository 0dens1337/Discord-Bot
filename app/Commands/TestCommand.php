<?php

namespace App\Commands;

use Discord\Parts\Interactions\Interaction;
use Laracord\Commands\Command;

class TestCommand extends Command
{
    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'test';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Проверка коннекта с сервером';

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
        return $this
            ->message('Ее ебут в клубах, парят на хатах, а она остается саммой желланой на этом сервере.')
            ->title('test')
            ->image('https://i.pinimg.com/736x/e3/42/c7/e342c7fce89f8401ff662acc73615f51.jpg')
            ->reply($message);
    }

    /**
     * The command interaction routes.
     */
//    public function interactions(): array
//    {
//        return [
//            'wave' => fn (Interaction $interaction) => $this->message('👋')->reply($interaction),
//        ];
//    }
}
