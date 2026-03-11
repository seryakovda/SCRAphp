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
//        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."После проверов и маршрутизации",'log.txt');
//        \models\ErrorLog::saveError($_REQUEST,'log.txt');

        $this->MODEL->regKey($_REQUEST['qrCode'],$_REQUEST['inOut'],$_REQUEST['typeCode']);
//        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."регистрация ключа",'log.txt');
        $this->MODEL->sendKey();
//        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."Фоновый процесс по отправке на сервер",'log.txt');

        //$this->MODEL->registrationScanQrCode();
        $answer = $this->MODEL->getDataByQrCode($_REQUEST['qrCode'],$_REQUEST['typeCode']);
//        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."Получены все данные",'log.txt');
        header("content-type:application/json");
        print json_encode($answer);
//        \models\ErrorLog::saveError(date('d.m.Y H:i:s')."Выплюнули в телефон",'log.txt');
//        \models\ErrorLog::saveError($answer,'log.txt');
//        \models\ErrorLog::saveError(json_encode($answer),'log.txt');

    }

    public function sendBinaryData()
    {
        \models\ErrorLog::saveError($_REQUEST['binaryData'],"sendBinaryData.txt");
//        \models\ErrorLog::saveError($this->MODEL->getEmarin($_REQUEST['binaryData']),"sendBinaryData.txt");
    }

    public function getPhoto()
    {
        ob_start();
        $errorImage = _G_session::ROOT_PATH().'/forms/SYS/WithOutPhoto.jpg';
        $d = new pList();
        if ($_REQUEST['idPhoto'] == "-1") {
//            \models\ErrorLog::saveError("-1",'log1.txt');
            $image = file_get_contents($errorImage);
        }
        else{
//            \models\ErrorLog::saveError("1 = ".$_REQUEST['idPhoto'],'log1.txt');
            $image = $d->where($d::ID,$_REQUEST['idPhoto'])->select($d::Picture)->fetchField($d::Picture);
            if ($image === false) {
//                \models\ErrorLog::saveError("false = ".$_REQUEST['idPhoto'],'log1.txt');
                $image = file_get_contents($errorImage);
            }
            if (strlen($image) < 1000) {
//                \models\ErrorLog::saveError("10000 = ".$_REQUEST['idPhoto'],'log1.txt');
//                \models\ErrorLog::saveError($errorImage,'log1.txt');
                $image = file_get_contents($errorImage);
            }
        }

//        \models\ErrorLog::saveError(date('d.m.Y H:i:s')." getPhoto end",'log1.txt');


        header("content-type:image/jpeg");
        header("Content-Length:" .(string)(strlen($image)) );
        echo $image;
        $output = ob_get_contents();
        ob_end_clean();
//        \models\ErrorLog::saveError($output,'img.jpg',"w+");
        echo $output;
    }
}