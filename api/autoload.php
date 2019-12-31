<?php
/**
 * Автозагрузчик файлов для классов.
 * 
 * - папка соответствует пространству имён
 * - название файла и название класса идентичны
 * - всё регистрозависимо
 * 
 * Файловая система:
 *  HTTP\
 *      Client.php
 *  autoload.php
 */

// HTTP Client
spl_autoload_register(function($class)
{
    $namespace = 'HTTP\\';

    if (substr($class, 0, strlen($namespace)) !== $namespace) return;

    $class = str_replace('\\', '/', $class);

    $path = __DIR__ .'/'. $class .'.php';
    if (is_readable($path)) require_once $path;
});

// Bitrix24 Rest Api
spl_autoload_register(function($class)
{
    $namespace = 'Bitrix24\\';

    if (substr($class, 0, strlen($namespace)) !== $namespace) return;

    $class = str_replace('\\', '/', $class);

    $path = __DIR__ .'/'. $class .'.php';
    if (is_readable($path)) require_once $path;
});

// New Api
spl_autoload_register(function($class)
{
    $namespace = 'NewApi\\';

    if (substr($class, 0, strlen($namespace)) !== $namespace) return;

    $class = str_replace('\\', '/', $class);

    $path = __DIR__ .'/'. $class .'.php';
    if (is_readable($path)) require_once $path;
});