<?php

class Logger {

    static function write_log($message, $name_file = false) {
        if ($name_file != false) {
            $file_path = $_SERVER['DOCUMENT_ROOT'].'/logs/'.$name_file.'.txt';
        } else {
            $file_path = $_SERVER['DOCUMENT_ROOT'].'/logs/log.txt';
        }
        $handle = fopen($file_path, "a+");
        fwrite ($handle, '['. date('d-m-Y H:i:s ').'] :: ' . iconv('utf-8', 'cp1251', $message) . "\r\n\r\n");
        fclose($handle);
    }

}