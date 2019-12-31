<?php

define('ROOT', __DIR__);

require_once 'settings.php';

require_once 'api/autoload.php';

require_once 'helper/autoload.php';

// файлы логов
$logMaxFilesize = 1024 * 1024; // 1 мб

$LogAll = new Log(ROOT .'/logs/log.txt', $logMaxFilesize);