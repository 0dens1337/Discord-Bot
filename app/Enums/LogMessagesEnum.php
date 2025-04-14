<?php

namespace App\Enums;

enum LogMessagesEnum: string
{
    case DELETION_MESSAGE = 'Лог удаления сообщения';
    case ACTION_MESSAGE = 'Лог действия';
    case VOICE_MESSAGE = 'Лог голосового канала';
}
