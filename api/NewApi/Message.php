<?php

namespace NewApi;

use HTTP\Client;
use HTTP\ClientInterface;

class Message extends ClientInterface
{
    /**
     * Send message
     * This endpoint delivers message to the client under specified conversation.
     * There are two delivery modes: synchronous and asyncronous. If message_id field in response is null or empty – it means asyncronous delivery.
     * You’ll receive a webhook with the delivery status if delivery is async.
     * 
     * @param array $parameters
     *  [
     *      'transport' => 'whatsapp',                                      String	Транспорт отправки (указывать «whatsapp»)
     *      'from' => '79991234567',                                        String	Номер телефона канала отправки в международном формате без спец.символов
     *      'to' => '79159876543',                                          String	Номер телефона получателя в международном формате без спец.символов
     *      'text' => 'Hi, guys!',                                          String	Текст сообщения (до 10000 символов)
     *      'content' => 'https://global-picture-hoster.com/pic123.jpg',    String	Ссылка на контент (Например, «https://picrutre-hoster.com/pic123.jpg»)
     *      'author' => 'Имя автора'                                        String	Имя автора сообщения (не обязательное, до 100 символов)
     *  ]
     * 
     * @return В случае успеха в ответ будет оправлен результат HTTP/1.1 201 OK и guid созданного сообщения
     *  {
     *      guid: "4baef03e-bb29-4593-a331-47f94b14cce7"
     *  }
     */
    public function send(array $parameters)
    {
        $params =
        [
            'method' => 'send_message',
            'httpMethod' => Client::POST,
            'httpParameters' => $parameters
        ];

        return $this->call($params);
    }
}