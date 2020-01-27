<?php

namespace HTTP;

/**
 * Класс для запросов по http протоколу. Написанный специально для организации подключения к API
 */
class Client
{
    const GET       =   'GET';
    const POST      =   'POST';
    const PUT       =   'PUT';
    const PATCH     =   'PATCH';
    const DELETE    =   'DELETE';

    public $curl;                       // дескриптор курл процесса
    protected $userPwd;                 // авторизационные данные для http протокола
    protected $url;                     // адрес запроса
    protected $headersRequest = [];     // заголовки текущего запроса (действуют только для одного запроса)
    protected $headers =                // заголовки запроса
    [
        'Accept' => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /**
     * @param array $parameters     Массив с параметрами для подключения к API
     *  [
     *      'apiUrl' => 'https://DOMAIN.bitrix24.ru/rest/tasks.task.add'    url api
     *      'apiKey' => ['Authorization' => 'dfsdfg342rfsdfgu67yfj7'] | 'dfsdfg342rfsdfgu67yfj7'    если ключ это массив, то добавляем его как заголовок; если это строка то пока не знаю
     *      'headers' => ['Cache-Control' => 'no-cache']                    заголовки которые нужно добавить или изменить
     *      'userPwd' => 'username:password'                                авторизационные данные для http протокола
     *  ]
     */
    public function __construct(array $parameters = [])
    {
        // считываем переданные параметры
        $headers = $parameters['headers'];
        if (!is_array($headers)) $headers = [];

        $apiKey = $parameters['apiKey'];

        $this->userPwd = $userPwd = $parameters['userPwd'];

        $apiUrl = $parameters['apiUrl'];
        if (is_null($apiUrl)) $apiUrl = '';


        $this->headersInit();
        $this->headersAdd($headers);

        if (is_array($apiKey))
            $this->headersAdd($apiKey);
        
        $this->url = $apiUrl;

        $this->curl = curl_init();
    }

    /**
     * Инициализируем массив заголовков и приводим ключи массива к нижнему регистру.
     */
    private function headersInit()
    {
        if (!is_array($this->headers))
            $this->headers = [];

        self::headersKeyToLow($this->headers);
    }

    /**
     * Возвращает список заголовков которые прикрепляются к запросам.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Возвращает массив заголовков для запроса curl.
     */
    public function getHeadersForRequest()
    {
        return self::headersJoin(array_merge($this->headers, $this->headersRequest));
    }

    /**
     * Возвращает адрес апи
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Добавляем или заменяем дополнительными заголовками.
     * 
     * @param array $headers    Добавляем заголовки к массиву заголовков запроса.
     */
    public function headersAdd(array $headers)
    {
        if (empty($headers)) return;

        self::headersKeyToLow($headers);
        $this->headers = array_merge($this->headers, $headers);
    }

    /**
     * Добавляем заголовки для текущего запроса.
     * 
     * @param array $headers    Добавляем заголовки к массиву текущих заголовков запроса.
     */
    public function headersRequestAdd(array $headers)
    {
        if (empty($headers)) return;

        self::headersKeyToLow($headers);
        $this->headersRequest = array_merge($this->headersRequest, $headers);
    }

    /**
     * Приводим ключи массива заголовков к нижнему регистру.
     * 
     * @param array &$headers   Массив заголовков, ключи которого нужно привести в нижний регистр
     * @return array    Вернёт массив с ключами в нижнем регистре
     *  [
     *      'accept' => 'application/json',
     *      'content-type' => 'application/json'
     *  ]
     */
    static public function headersKeyToLow(array &$headers)
    {
        if (empty($headers)) return;

        $headersNew = $headers;
        $headers = [];

        foreach($headersNew as $key => $val)
        {
            $headers[strtolower($key)] = $val;
        }
    }

    /**
     * Склеивает заголовки "ключ: значение"
     * 
     * @param array $headers    Массив заголовков который нужно склеить
     * 
     * @return array    Вернёт склеенный массив
     *  [
     *      0 => 'Accept: application/json',
     *      1 => 'Content-Type: application/json'
     *  ]
     */
    static public function headersJoin(array $headers)
    {
        $headersJoin = [];
        foreach ($headers as $key => $val)
        {
            $headersJoin[] = $key .': '. $val;
        }

        return $headersJoin;
    }

    /**
     * Сбрасываем настройки curl до стандартных.
     */
    protected function curlReset()
    {
        $this->headersRequest = [];

        curl_reset($this->curl);

        curl_setopt_array($this->curl,
        [
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => 1,
            CURLINFO_HEADER_OUT => 1,
            CURLOPT_FOLLOWLOCATION => 1
        ]);

        if (is_string($this->userPwd)) 
            curl_setopt($this->curl, CURLOPT_USERPWD, $this->userPwd);
    }

    public function __destruct()
    {
        curl_close($this->curl);
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
    public function call(array $parameters)
    {
        $this->curlReset();

        // считываем переданные параметры
        $method = $parameters['method'];
        if (is_null($method)) $method = '';

        $httpMethod = $parameters['httpMethod'];
        if (is_null($httpMethod)) $httpMethod = self::GET;

        $httpParameters = $parameters['httpParameters'];
        if (!is_array($httpParameters)) $httpParameters = [];

        $url = $parameters['url'];
        if (is_null($url)) $url = '';

        $headers = $parameters['headers'];
        if (!is_array($headers)) $headers = [];


        $this->headersRequestAdd($headers);

        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $httpMethod);

        $fullUrl = !empty($url) ? $url : $this->url . $method;

        if ($httpMethod == self::GET)
        {
            $parameters0 = !empty($httpParameters) ? http_build_query($httpParameters) : '';

            curl_setopt($this->curl, CURLOPT_URL, $fullUrl .(!empty($parameters0) ? '?'. $parameters0 : ''));
            curl_setopt($this->curl, CURLOPT_POSTFIELDS, '');
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->getHeadersForRequest());
        }
        else
        {
            curl_setopt($this->curl, CURLOPT_URL, $fullUrl);

            if (empty($httpParameters)) // не передаём параметры
            {
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, '');
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->getHeadersForRequest());
            }
            else if (self::isUploadFile($this->headers) || self::isUploadFile($this->headersRequest)) // отправляем файл
            {
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $httpParameters);
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->getHeadersForRequest());
            }
            else // запрос с передачей параметров
            {
                $parameters0 = json_encode($httpParameters);

                $this->headersRequestAdd(['Content-Length: '. strlen($parameters0)]);

                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $parameters0);
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->getHeadersForRequest());
            }
        }


        $result = curl_exec($this->curl);
    
        $error_msg = '';
        if (curl_errno($this->curl))
            $error_msg = curl_error($this->curl);
        
        $curl_status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $header_size = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $header_out = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        $info = curl_getinfo($this->curl);
        
        return
        [
            'status'    => $curl_status,
            'result'    => json_decode($result, true),
            'error'     => $error_msg,
            'headers'   => $header_out,
            'info'      => $info
        ];
    }

    /**
     * Скачивание файла
     * 
     * @param array $parameters     // параметры для скачивания файла
     *  [
     *      'filepath' => '/home/mysite.ru/www/images/image1.jpg',  // путь загрузки файла
     *      'url' => '',                                            // адрес запроса файла, если пустой то используется $this->url
     *      'headers' => ['Cache-Control' => 'no-cache']            // заголовки которые нужно добавить или изменить к текущему запросу
     *  ]
     */
    public function download(array $parameters)
    {
        $this->curlReset();

        // считываем переданные параметры
        $filepath = $parameters['filepath'];
        if (is_null($filepath)) return;

        $url = $parameters['url'];
        if (is_null($url)) $url = '';

        $headers = $parameters['headers'];
        if (!is_array($headers)) $headers = [];

        $this->headersRequestAdd($headers);
        
        // создаём файл
        $fp = fopen($filepath, 'w');
        
        if (empty($url)) $url = $this->url;

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->getHeadersForRequest());
        curl_setopt($this->curl, CURLOPT_FILE, $fp);

        $result = curl_exec($this->curl);
        
        fclose($fp);

        $success = true;
        if (filesize($filepath) == 0) // если файл пустой то удаляем его
        {
            unlink($filepath);
            $success = false;
        }

        $error_msg = '';
        if (curl_errno($this->curl))
            $error_msg = curl_error($this->curl);
        
        $curl_status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
        $header_out = curl_getinfo($this->curl, CURLINFO_HEADER_OUT);
        $info = curl_getinfo($this->curl);
        
        return
        [
            'status' => $curl_status,
            'isSuccess' => $success,
            'result' => json_decode($result, true),
            'error' => $error_msg,
            'headers' => $header_out,
            'info' => $info
        ];
    }

    /**
     * Проверяем запрос что он будет на отправку файла
     * 
     * @param array $headers    // массив заголовков
     */
    static private function isUploadFile(array $headers)
    {
        foreach ($headers as $val)
        {
            if (preg_match('/multipart\/form-data/i', $val)) return true;
        }

        return false;
    }
}