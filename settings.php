<?php

define('IS_TEST', true);

if (IS_TEST) // тестовые настройки
{
    // вебхук
    define('WEBHOOK_DOMAIN', 'b24-0okkuo');
    define('WEBHOOK_USER', '1');
    define('WEBHOOK_KEY', '34w9vof6axcmdrr0');
}
else // боевые настройки
{
    // вебхук
    define('WEBHOOK_DOMAIN', '');
    define('WEBHOOK_USER', '');
    define('WEBHOOK_KEY', '');
}

define('DATE_FORMAT', 'd.m.Y H:i:s');     // формат даты

date_default_timezone_set('Asia/Novosibirsk');

//set_time_limit(600);    // максимальное время выполнения скрипта