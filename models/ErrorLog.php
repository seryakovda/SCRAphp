<?php

namespace models;

class ErrorLog
{
    static function saveError($value, $fileName = 'MyErrorLog.txt',$typeSaveMode = "a")
    {
        ob_start();
        print_r($value);
        $out = ob_get_contents();
        ob_end_clean();

        $file = fopen($_SERVER['DOCUMENT_ROOT']."/TMP/".$fileName, $typeSaveMode);

        fwrite($file, $out . "\r\n");

        fclose($file);
    }
}