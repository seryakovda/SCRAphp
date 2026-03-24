<?php
/**
 * Является единственной точкой входа для всех инструкциф приложения
 */

require "Properties/error_reporting.php";
/**
 * spl_autoload_register.php Содержит
 * функцию spl_autoload_register которая автоматически подгружает клас по его nameSpace
 */
require "spl_autoload.php";


set_time_limit(0);

if (array_key_exists('sessionHandle',$_REQUEST)){
    session_id($_REQUEST['sessionHandle']);
}



session_start();
//\models\ErrorLog::saveError($_REQUEST,"BatMon.txt");
// переводим миллисекунды в секунды
$seconds = $_REQUEST['timestamp'] / 1000;

// получаем дату в формате для MSSQL
$date = date('Y-m-d H:i:s', $seconds);
//\models\ErrorLog::saveError($date,"BatMon.txt");
