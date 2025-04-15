<?php

namespace App\Enums;

enum RoleMessageEnum: string
{
    case PICK_A_COLOR = 'Можешь выбрать цвет ника на сервере';
    case SUCCESS_UPDATED = 'Цвет успешно обновлен!';
    case IMAGE = 'https://i.pinimg.com/736x/c9/6c/5f/c96c5f93c56531e90b26c924e06c9a1a.jpg';
    case TEMPLATE = "```Для особо крутых челов сервера\nмы предоставляем возможность выбрать\nсебе не только цвет ника, а также эмозди\nв нике. Помни что для использования тебе нужно\nиметь роль donater```";
    case NOT_A_DONATER = 'Эта функция только для ребята которые всячески поддерживают сервер материально.';
    case NO_RIGHTS = 'У вас нет прав для использования этого действия.';
}
