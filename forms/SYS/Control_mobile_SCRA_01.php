<?php

namespace forms\SYS;

use DB\Table\pList;
use DB\Table\Users;
use \models\_G_session;
use \models\ErrorLog;

/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 24.09.2019
 * Time: 18:00
 */
class Control_mobile_SCRA_01 extends Control
{
    function __construct()
    {
        $this->MODEL = new MODEL_mobile_SCRA_01();
    }



    public function getDataByQrCode()
    {
        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."После проверов и маршрутизации",'log.txt');

        $this->MODEL->regKey($_REQUEST['qrCode'],$_REQUEST['inOut'],$_REQUEST['typeCode']);
        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."регистрация ключа",'log.txt');
        $this->MODEL->sendKey();
        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."Фоновый процесс по отправке на сервер",'log.txt');

        //$this->MODEL->registrationScanQrCode();
        $answer = $this->MODEL->getDataByQrCode($_REQUEST['qrCode'],$_REQUEST['typeCode']);
        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."Получены все данные",'log.txt');
        header("content-type:application/json");
        print json_encode($answer);
        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."Выплюнули в телефон",'log.txt');
    }

    public function sendBinaryData()
    {
        \models\ErrorLog::saveError($_REQUEST['binaryData'],"sendBinaryData.txt");
//        \models\ErrorLog::saveError($this->MODEL->getEmarin($_REQUEST['binaryData']),"sendBinaryData.txt");
    }

    public function getPhoto()
    {
        $d = new pList();
        $image = $d->where($d::ID,$_REQUEST['idPhoto'])->select($d::Picture)->fetchField($d::Picture);
        header("content-type:image/jpeg");
        header("Content-Length:" .(string)(strlen($image)) );
        echo $image;
    }
}