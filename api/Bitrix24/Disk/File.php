<?php

namespace Bitrix24\Disk;

use HTTP\Client;
use HTTP\ClientInterface;

class File extends ClientInterface
{
    /**
     * Возвращает файл по идентификатору.
     * 
     * @param int $id Идентификатор файла.
     * @return
     *  "result":
     *  {
     *      "ID": "10", //идентификатор
     *      "NAME": "2511.jpg", //название файла
     *      "CODE": null, //символьный код
     *      "STORAGE_ID": "4", //идентификатор хранилища
     *      "TYPE": "file",
     *      "PARENT_ID": "8", //идентификатор родительской папки
     *      "DELETED_TYPE": "0", //маркер удаления
     *      "CREATE_TIME": "2015-04-24T10:41:51+03:00", //время создания
     *      "UPDATE_TIME": "2015-04-24T15:52:43+03:00", //время изменения
     *      "DELETE_TIME": null, //время перемещения в корзину
     *      "CREATED_BY": "1", //идентификатор пользователя, который создал файл
     *      "UPDATED_BY": "1", //идентификатор пользователя, который изменил файл
     *      "DELETED_BY": "0", //идентификатор пользователя, который переместил в корзину файл
     *      "DOWNLOAD_URL": "https://test.bitrix24.ru/disk/downloadFile/10/?&ncc=1&filename=2511.jpg&auth=******", //возвращает url для скачивания файла приложением
     *      "DETAIL_URL": "https://test.bitrix24.ru/workgroups/group/3/disk/file/2511.jpg" //ссылка на страницу детальной информации о файле
     *  }
     */
    public function get($id)
    {
        $params =
        [
            'method' => 'disk.file.get',
            'httpMethod' => Client::GET,
            'httpParameters' => ['id' => $id]
        ];

        return $this->call($params);
    }
}