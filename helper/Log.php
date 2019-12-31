<?php

/**
 * Запись логов в файл. Будет 2 файла, один с префиксом _past,
 * поэтому максимальный размер файла учитывается вместе.
 * Указываете тот размер который хотите чтобы выделялся под 2 файла логов.
 */
class Log
{
    public function __construct($filename, $maxFilesize = 0)
    {
        $this->filename = $filename;
        $this->maxFilesize = (int)($maxFilesize / 2);
    }

    public function write($data)
    {
        file_put_contents($this->filename, $data.PHP_EOL, FILE_APPEND);

        $this->checkSize();
    }

    private function checkSize()
    {
        if ($this->maxFilesize != 0)
        {
            if (filesize($this->filename) >= $this->maxFilesize) $this->copyLog();
        }
    }

    private function copyLog()
    {
        $filename = basename($this->filename);
        $filenameArr = explode('.', $filename);
        $filenameArr[count($filenameArr) - 2] .= '_past';
        $filename = implode('.', $filenameArr);

        copy($this->filename, dirname($this->filename) .'/'. $filename);
        unlink($this->filename);
    }
}