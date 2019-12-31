<?php
// если нужно из js сценариев принимать запрос
// header('Access-Control-Allow-Origin: *');

// сверяем хэш доступа к скрипту
$hash = 'gfjlweo3094l5mm4j62ld9krm56';
if ($_REQUEST['hash'] != $hash) exit;

// принимаем входные данные
$leadId = $_REQUEST['id'];
$comment = $_REQUEST['comment'];

// обязательные поля
if (!isset($leadId, $comment)) exit;


require_once 'autoload.php';


// декодируем входные данные
// $leadId = urldecode($leadId);
// $comment = urldecode($comment);

// форматируем входные данные
$leadId = intval($leadId);


/**
 * подключения API
 */

// Bitrix24 Rest Api
$clientBitrixParameters =
[
    'apiUrl' => 'https://'. WEBHOOK_DOMAIN .'.bitrix24.ru/rest/'. WEBHOOK_USER .'/'. WEBHOOK_KEY .'/',
    'headers' => ['Cache-Control' => 'no-cache']
];

$clientBitrix = new \HTTP\Client($clientBitrixParameters);


$Timeline = new \Bitrix24\CRM\Timeline($clientBitrix);
$leadComment = $Timeline->initAdd($leadId, 'lead');


/**
 * Отправляет сообщение в комментарий лида о смене стадии.
 * При смене стадии лидов, срабатывает веб хук в роботах.
 */

$leadCommentResult = $leadComment($comment);

Debug::print($leadCommentResult);