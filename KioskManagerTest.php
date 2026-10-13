<?php
use models\ErrorLog;
use mysql_xdevapi\Exception;
use views\mPrint;

session_start();
require "Properties/error_reporting.php";
require "spl_autoload.php";
header("content-type:application/json");
$d = new \DB\Table\BatMonArduinoSettings();
$data  = $d->where($d::keyAPI,$_SERVER['HTTP_X_API_KEY'])->select()->fetchAll();
$reboot = $data[$d::reboot] == "1" ? true : false;
$deviceId = $data[$d::deviceId];
$d = new \DB\Table\BatMonArduino();

$conn = new \DB\Connect();
$voltage = $conn->complexQuery(
    "select {$d::voltage} from BatMonArduino where {$d::deviceId}='$deviceId' order by {$d::dateTimeIndex} DESC limit 1"
)->fetchField($d::voltage);
$voltage = $voltage * 1.0000;
$sendVoice = 0;
if ($voltage < 10.5)
    $sendVoice = 1;

$ret = Array ("reboot"=>$reboot,"sendVoice"=>$sendVoice);
ErrorLog::saveError($ret,"KioskManager.txt");

print json_encode($ret);
