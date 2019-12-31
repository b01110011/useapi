<?php

namespace Bitrix24\CRM;

use HTTP\Client;
use HTTP\ClientInterface;

class Timeline extends ClientInterface
{
    /**
     * Инициализируем функцию для многократного добавления в комментарий лида или сделки в CRM.
     * 
     * @param int $entityId     айди лида или сделки
     * @param int $entityType   тип, лид или сделка
     * 
     * @return Функция для добавления комментария в CRM.
     */
    public function initAdd($entityId, $entityType)
    {
        return function($comment) use($entityId, $entityType)
        {
            $parameters =
            [
                'fields' =>
                [
                    'ENTITY_ID' => $entityId,
                    'ENTITY_TYPE' => $entityType,
                    'COMMENT' => $comment
                ]
            ];

            return $this->add($parameters);
        };
    }

    /**
     * Добавляет новый комментарий в таймлайн.
     * 
     * @param array $parameters
     *  [
     *      'fields' =>
     *      [
     *          'ENTITY_ID' => 10,
     *          'ENTITY_TYPE' => 'deal',
     *          'COMMENT' => 'New comment was added'
     *      ]
     *  ]
     */
    public function add(array $parameters)
    {
        $params =
        [
            'method' => 'crm.timeline.comment.add',
            'httpMethod' => Client::GET,
            'httpParameters' => $parameters
        ];

        return $this->call($params);
    }
}