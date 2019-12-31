<?php

namespace Bitrix24\ChatAndNotifications;

use HTTP\Client;
use HTTP\ClientInterface;

class Message extends ClientInterface
{
    /**
     * Отправка сообщения в чат.
     * 
     * @param array $parameters
     *  [
     *      'DIALOG_ID' => 'chat13',
     *      'MESSAGE' => 'Текст сообщения',
     *      'SYSTEM' => 'N',
     *      'ATTACH' => '',
     *      'URL_PREVIEW' => 'Y',
     *      'KEYBOARD' => '',
     *      'MENU' => '',
     *  ]
     */
    public function add(array $parameters)
    {
        $params =
        [
            'method' => 'im.message.add',
            'httpMethod' => Client::GET,
            'httpParameters' => $parameters
        ];

        return $this->call($params);
    }
}