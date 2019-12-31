<?php

namespace Bitrix24\Disk;

use HTTP\Client;
use HTTP\ClientInterface;

class Storage extends ClientInterface
{
    /**
     * Возвращает список файлов и папок, которые находятся непосредственно в корне хранилища.
     */
    public function getChildren($diskId, array $filter = [])
    {
        $parameters = ['id' => $diskId];
        if (!empty($filter)) $parameters['filter'] = $filter;

        $params =
        [
            'method' => 'disk.storage.getchildren',
            'httpMethod' => Client::GET,
            'httpParameters' => $parameters
        ];

        return $this->call($params);
    }
}