<?php

namespace App\Enums;

enum ActionTypeEnum: int
{
    case CREATE_CHANNEL = 10;
    case UPDATE_CHANNEL = 11;
    case DELETE_CHANNEL = 12;
    case CREATE_EMOJI = 60;
    case UPDATE_EMOJI = 61;
    case DELETE_EMOJI = 62;
    case BAN_USER = 22;
    case CREATE_INTEGRATION = 80;
    case UPDATE_INTEGRATION = 81;
    case CREATE_INVITE = 40;
    case UPDATE_INVITE = 41;
    case KICK_USER = 20;
    case USER_DISCONNECTED = 27;
    case USER_MOVED = 26;
    case UPDATE_USER_ROLE = 25;
    case UPDATE_USER_INFO = 24;
    case DELETE_MESSAGES = 73;
    case DELETE_MESSAGE = 72;
    case CREATE_ROLE = 30;
    case DELETE_ROLE = 32;
    case UPDATE_ROLE = 31;
    case UNBAN_USER = 23;
    case UPDATE_VOICE_STATUS = 192;
    case DELETE_VOICE_STATUS = 193;
    case CREATE_WEBHOOK = 50;

    public function description(): string
    {
        return match ($this) {
            self::CREATE_CHANNEL => 'Создание канала',
            self::UPDATE_CHANNEL => 'Обновление канала',
            self::DELETE_CHANNEL => 'Удаление канала',
            self::CREATE_EMOJI => 'Создание Эмодзи',
            self::UPDATE_EMOJI => 'Обновление Эмодзи',
            self::DELETE_EMOJI => 'Удаление Эмодзи',
            self::BAN_USER => 'Бан (73 рус) пользователя',
            self::CREATE_INTEGRATION => 'Создание интеграции',
            self::UPDATE_INTEGRATION => 'Обновление интеграции',
            self::CREATE_INVITE => 'Создание приглашения',
            self::UPDATE_INVITE => 'Обновление приглашения',
            self::KICK_USER => 'Кик с сервера',
            self::USER_DISCONNECTED => 'Пользователь отключился',
            self::USER_MOVED => 'Пользователь перемещен в другой канал',
            self::UPDATE_USER_ROLE => 'Обновление роли пользователя',
            self::UPDATE_USER_INFO => 'Информация о пользователе обновлена',
            self::DELETE_MESSAGES => 'Несколько сообщений пользователя были удалены',
            self::DELETE_MESSAGE => 'Удаление сообщения',
            self::CREATE_ROLE => 'Создание роли',
            self::DELETE_ROLE => 'Удаление роли',
            self::UPDATE_ROLE => 'Обновление роли',
            self::UNBAN_USER => 'Разбан пользователя',
            self::UPDATE_VOICE_STATUS => 'Обновление статуса голосового канала',
            self::DELETE_VOICE_STATUS => 'Удаление статуса голосового канала',
            self::CREATE_WEBHOOK => 'Создание вебхука',
        };
    }
}
