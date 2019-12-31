<?php

abstract class SpeedTest
{
    static public $time = 0;

    static public function start()
    {
        self::$time = microtime(true);
    }

    static public function end()
    {
        return self::$time = microtime(true) - self::$time;
    }
}