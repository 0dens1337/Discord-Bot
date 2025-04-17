<?php

namespace App\Enums;

enum LogMessagesEnum: string
{
    case DELETION_MESSAGE = 'Лог удаления сообщения';
    case ACTION_MESSAGE = 'Лог действия';
    case VOICE_MESSAGE = 'Лог голосового канала';
    case JOINED_TO_THE_VOICE_CHANNEL = 'вошел в голосовой канал';
    case LEFT_THE_VOICE_CHANNEL = 'вышел из голосового канала';
}
