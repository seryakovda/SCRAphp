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


$timestamp =            $_REQUEST['timestamp'] ;
$keyAPI =               $_REQUEST['keyAPI'];
$batteryLevel =         $_REQUEST['batteryLevel'];
$batteryTemperature =   $_REQUEST['batteryTemperature'];
$voltage =              $_REQUEST['voltage'];
if ($_REQUEST['chargingStatus']  == "false") {
    $chargingStatus = "0";
} else {
    $chargingStatus = "1";
}

// получаем дату в формате для MSSQL
$dateTime = date('Y-m-d H:i:s',(int) ($timestamp / 1000));

$dateNowD = new DateTime();
$dateNow = $dateNowD->format('Y-m-d H:i:s');
$dateNowD->modify('-3 minute');
$dateNowOld =  $dateNowD->format('Y-m-d H:i:s');


try{
    $d = new \DB\Table\BatMon();

    $d->where($d::dateTimeIndex,$dateNowOld,"<")->delete();

    $d
        ->set($d::dateTimeIndex,$dateNow)
        ->set($d::keyAPI,$keyAPI)
        ->set($d::batteryLevel,$batteryLevel)
        ->set($d::chargingStatus,$chargingStatus)
        ->set($d::voltage,$voltage)
        ->insert();

}catch ( PDOException $e){

}


$R = new models\RefreshDataFormPS();
if ($R->testConnection()){
    $R->getSession();
    if ($R->authorizationOnThePS()){
        $R->BatteryMonitorEvent();
    }
}
