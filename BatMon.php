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

$R = new models\RefreshDataFormPS();
if ($R->testConnection()){
    $R->getSession();
    if ($R->authorizationOnThePS()){
        $R->BatteryMonitorEvent();
    }
}
