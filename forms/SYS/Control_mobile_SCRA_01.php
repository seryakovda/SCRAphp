<?php

namespace forms\SYS;

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
        \models\ErrorLog::saveError("getDataByQrCode");
        //$this->MODEL->registrationScanQrCode($_REQUEST['qrCode']);
        $answer = $this->MODEL->getDataByQrCode($_REQUEST['qrCode'],$_REQUEST['typeCode']);
        \models\ErrorLog::saveError($answer);
        header("content-type:application/json");
        print json_encode($answer);
    }

    public function sendBinaryData()
    {
        \models\ErrorLog::saveError($_REQUEST['binaryData'],"sendBinaryData.txt");
//        \models\ErrorLog::saveError($this->MODEL->getEmarin($_REQUEST['binaryData']),"sendBinaryData.txt");
    }
}