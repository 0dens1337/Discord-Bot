<?php

namespace App\Enums;

enum EmojiMessageEnum: string
{
    case PICK_AN_EMOJI = 'Можешь выбрать себе емодзи в ник';
    case SUCCESS_UPDATED = 'Эмодзи успешно обновлен!';
    case EMOJI_TEMPLATE = "ваш крутой ник"."<:8c0948f9874f433294db39fa7acafb48:1359566089360769115>";
}
