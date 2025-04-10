<?php

namespace App\Enums;

enum EmojiMessageEnum: string
{
    case PICK_AN_EMOJI = 'Можешь выбрать себе емодзи в ник';
    case COMMAND_SUCESS = 'Команда с эмодзи успешно выполнена!';
    case SUCCESS_UPDATED = 'Эмодзи успешно обновлен!';
}
