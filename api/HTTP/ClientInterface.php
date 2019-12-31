<?php

namespace HTTP;

/**
 * Класс для подключения клиента к api методам
 */
abstract class ClientInterface
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Вызов метода или запрос url адреса
     * 
     * @param array $parameters     Параметры вызова
     *  [
     *      'method' => 'tasks.task.get',                   // метод api к которому обращаемся
     *      'httpMethod' => Client::GET,                    // http метод GET POST PUT PATCH DELETE
     *      'httpParameters' => ['taskId' => 1],            // параметры запроса
     *      'url' => 'https://DOMAIN.bitrix24.ru/rest/tasks.task.add',  // полный путь запроса, используется вместо method
     *      'headers' => ['Cache-Control' => 'no-cache']    // заголовки которые нужно добавить или изменить к текущему запросу
     *  ]
     * 
     * @return array    Возвращает данные текущего запроса (статус, заголовки, результат ...)
     */
    protected function call(array $parameters)
    {
        return $this->client->call($parameters);
    }
}