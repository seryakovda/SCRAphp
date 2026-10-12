<?php
// -- Запуск каждые три минуты
/*
/3     *    * * *  php /var/www/html/scra/Cron_GetBatMonSettings.php  > /dev/null 2>&1
*/
use DB\Proc\Proc_TriggerNumberCamera;
use views\mPrint;

session_start();
require "Properties/error_reporting.php";
require "spl_autoload.php";

$R = new models\RefreshDataFormPS();
if ($R->testConnection()){
    $R->getSession();
    if ($R->authorizationOnThePS()){
        $R->getBatMonSettings();
    }
}