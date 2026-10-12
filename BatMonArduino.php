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

$deviceId =             $_REQUEST['deviceId'];
$f_relayPin_trigger =   $_REQUEST['f_relayPin_trigger'] ;
$f_restart_btn =        $_REQUEST['f_restart_btn'];
$f_restart_trigger =    $_REQUEST['f_restart_trigger'];
$voltage =              $_REQUEST['voltage'];

$dateNowD = new DateTime();
$dateNow = $dateNowD->format('Y-m-d H:i:s');

try{
    $d = new \DB\Table\BatMonArduino();

    $d
        ->set($d::dateTimeIndex,        $dateNow)
        ->set($d::deviceId,             $deviceId)
        ->set($d::f_relayPin_trigger,   $f_relayPin_trigger)
        ->set($d::f_restart_btn,        $f_restart_btn)
        ->set($d::f_restart_trigger,    $f_restart_trigger)
        ->set($d::voltage,              $voltage)
        ->insert();

}catch ( PDOException $e){

}

$R = new models\RefreshDataFormPS();
if ($R->testConnection()){
    $R->getSession();
    if ($R->authorizationOnThePS()){
        $R->BatteryMonitorEventArduino();
    }
}

$d = new \DB\Table\BatMonArduinoSettings();
print $d->where($d::deviceId,$deviceId)->select($d::address)->fetchField($d::address);
