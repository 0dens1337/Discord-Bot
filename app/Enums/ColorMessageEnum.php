<?php

namespace App\Enums;

enum ColorMessageEnum: string
{
    case PICK_A_COLOR = 'Можешь выбрать цвет ника на сервере';
    case SUCCESS = 'Команда с цветами успешно выполнена!';
    case SUCCESS_UPDATED = 'Роль успешно обновлена!';
    case TEMPLATE = 'Текст сообщения';
}
