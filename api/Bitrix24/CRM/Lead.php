<?php

namespace Bitrix24\CRM;

use HTTP\Client;
use HTTP\ClientInterface;

class Lead extends ClientInterface
{
    /**
     * Возвращает список лидов по фильтру.
     * При выборке используйте маски:
     * - '*' для выборки всех полей (без пользовательских и множественных)
     * - 'UF_*' для выборки всех пользовательских полей (без множественных)
     * 
     * @param array $parameters
     *  [
     *      'order' => ['STATUS_ID' => 'ASC'],
     *      'filter' => ['>OPPORTUNITY' => 0, '!STATUS_ID' => 'CONVERTED'],
     *      'select' => ['ID', 'TITLE', 'STATUS_ID', 'OPPORTUNITY', 'CURRENCY_ID']
     *  ]
     */
    public function list(array $parameters)
    {
        $params =
        [
            'method' => 'crm.lead.list',
            'httpMethod' => Client::GET,
            'httpParameters' => $parameters
        ];

        return $this->call($params);
    }
}