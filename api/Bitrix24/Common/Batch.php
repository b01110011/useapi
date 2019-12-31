<?php

namespace Bitrix24\Common;

use HTTP\Client;
use HTTP\ClientInterface;

class Batch extends ClientInterface
{
    /**
     * Выполнение пакета запросов.
     * В некоторых случаях возникает необходимость отправить несколько запросов подряд.
     * 
     * @param array $parameters
     *  [
     *      'halt' => 0,
     *      'cmd' =>
     *      [
     *          'find_contact' => 'crm.duplicate.findbycomm?'
     *              .http_build_query([
     *                  'entity_type' => 'CONTACT',
     *                  'type' => 'PHONE',
     *                  'values' => ['+79625011243']
     *              ]),
     *          'get_contact' => 'crm.contact.get?'
     *              .http_build_query([
     *                  'id' => '$result[find_contact][CONTACT][0]',
     *              ]),
     *          'get_company' => 'crm.company.get?'
     *              .http_build_query([
     *                  'id' => '$result[get_contact][COMPANY_ID]',
     *                  'select' => ['*'],
     *              ])
     *      ]
     *  ]
     */
    public function do(array $parameters)
    {
        $params =
        [
            'method' => 'batch',
            'httpMethod' => Client::POST,
            'httpParameters' => $parameters
        ];

        return $this->call($params);
    }
}