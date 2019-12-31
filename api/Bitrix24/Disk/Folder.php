<?php

namespace Bitrix24\Disk;

use HTTP\Client;
use HTTP\ClientInterface;

class Folder extends ClientInterface
{
    /**
     * Загружает новый файл в указанную папку.
     */
    public function uploadFile($folderId, $fullpath, array $data = [])
    {
        $parameters = ['id' => $folderId];
        if (!empty($data)) $parameters['data'] = $data;

        $params =
        [
            'method' => 'disk.folder.uploadfile',
            'httpMethod' => Client::GET,
            'httpParameters' => $parameters
        ];

        $upload = $this->call($params);

        $params2 =
        [
            'url' => $upload['result']['uploadUrl'],
            'httpMethod' => Client::POST,
            'httpParameters' => [$upload['result']['field'] => curl_file_create($fullpath)],
            'headers' => ['Content-Type' => 'multipart/form-data']
        ];

        return $this->call($params2);
    }

    /**
     * Возвращает список файлов и папок, которые находятся непосредственно в папке.
     */
    public function getChildren($folderId, array $filter = [])
    {
        $parameters = ['id' => $folderId];
        if (!empty($filter)) $parameters['filter'] = $filter;

        $params =
        [
            'method' => 'disk.folder.getchildren',
            'httpMethod' => Client::GET,
            'httpParameters' => $parameters
        ];

        return $this->call($params);
    }
}