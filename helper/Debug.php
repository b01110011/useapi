<?php

abstract class Debug
{
    static public function print($var)
    {
        print '<pre>'. print_r($var, true) .'</pre>';
    }
}