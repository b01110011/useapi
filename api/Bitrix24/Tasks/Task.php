<?php

namespace Bitrix24\Tasks;

use HTTP\Client;
use HTTP\ClientInterface;

class Task extends ClientInterface
{
    /**
     * Метод обновляет задачу.
     * 
     * @param int $taskId айди задачи
     * @param array $fields поля задачи
     */
    public function update($taskId, array $fields)
    {
        $parameters =
        [
            'taskId' => $taskId,
            'fields' => $fields
        ];

        $params =
        [
            'method' => 'tasks.task.update',
            'httpMethod' => Client::GET,
            'httpParameters' => $parameters
        ];

        return $this->call($params);
    }

    /**
     * Возвращает информацию о конкретной задаче.
     * 
     * @param int $taskId айди задачи
     * @param array $select поля задачи
     */
    public function get($taskId, array $select = [])
    {
        $parameters = ['taskId' => $taskId];
        if (!empty($select))
            $parameters['select'] = $select;

        $params =
        [
            'method' => 'tasks.task.get',
            'httpMethod' => Client::GET,
            'httpParameters' => $parameters
        ];

        return $this->call($params);
    }
}