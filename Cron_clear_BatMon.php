<?php
// -- Запуск каждые три минуты
/*
/3     *    * * *  php /var/www/html/scra/Cron_clear_BatMon.php  > /dev/null 2>&1
*/
use DB\Proc\Proc_TriggerNumberCamera;
use views\mPrint;

session_start();
require "Properties/error_reporting.php";
require "spl_autoload.php";

$dateNowD = new DateTime();
$dateNowD->modify('-3 minute');
$dateNowOld =  $dateNowD->format('Y-m-d H:i:s');

    $d = new \DB\Table\BatMon();

    $d->where($d::dateTimeIndex,$dateNowOld,"<")->delete();