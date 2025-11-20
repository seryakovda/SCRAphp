<?php

use DB\Table\DSSL_EventNumberCamera;

require "Properties/error_reporting_DEV.php";
require "spl_autoload.php";

$ArrFiles = Array();

$camera = 'None';
foreach ($_FILES as $key => $f){
    if ($f['name'] == "anpr.xml"){
        $text = file_get_contents($f['tmp_name']);
        $xml = simplexml_load_string($text);

        if (property_exists($xml,'ipAddress')) {
            $camera = $xml->ipAddress;
        }

        $xml->dateTime = str_replace("T"," ",$xml->dateTime);
        $xml->dateTime = str_replace("+07:00","",$xml->dateTime);
        $xml->dateTime = str_replace("-07:00","",$xml->dateTime);

        $dateTime = date("d.m.Y H:i:s",strtotime($xml->dateTime));// MSSQl
        $dateTime = date("Y-m-d H:i:s",strtotime($xml->dateTime));// MySQl

        $dateTimeFile =  date ("d.m.Y H:i:s");// MSSQl
        $dateTimeFile =  date ("Y-m-d H:i:s");// MySQl

        $licensePlate = $xml->ANPR->licensePlate;
        $replacements = [
            'A' => 'А', 'a' => 'а',
            'B' => 'В', 'b' => 'ь',
            'C' => 'С', 'c' => 'с',
            'E' => 'Е', 'e' => 'е',
            'H' => 'Н', 'h' => 'н',
            'K' => 'К', 'k' => 'к',
            'M' => 'М', 'm' => 'т',
            'O' => 'О', 'o' => 'о',
            'P' => 'Р', 'p' => 'р',
            'T' => 'Т', 't' => 'т',
            'X' => 'Х', 'x' => 'х',
            'Y' => 'У','y' => 'у'
        ];

        $licensePlate = strtoupper($licensePlate);
        if ($licensePlate !='UNKNOWN')
            $licensePlate =  strtr($licensePlate, $replacements);
    }

    $rrName = explode(".",$f['name']);

    if (array_pop($rrName) == 'jpg')
        $ArrFiles[$f['name']] = filesize($f['tmp_name']);
}
if ($camera != 'None'){
    $maxFileName = '';
    if (is_array($ArrFiles))
        if (count($ArrFiles)>0)
            $maxFileName = array_keys($ArrFiles, max($ArrFiles))[0];

    $save = true;
    if ($camera =='10.13.17.35') { // для этой камеры сохраняем только те данный которые иожержат инфу о фотографиях
        if (count($xml->ANPR->pictureInfoList->pictureInfo) == 0)
            $save = false;
    }

    if ($save){

        $d1 = new DSSL_EventNumberCamera();
            $id_event = $d1
                ->set($d1::xmlData,$text)
                ->set($d1::number,$licensePlate)
                ->set($d1::dateTimeEvent,$dateTime)
                ->set($d1::dateTimeFile,$dateTimeFile)
                ->set($d1::ipCamera,$camera)
                ->insert();


        foreach ($_FILES as $key => $f) {


            if (array_key_exists($f['name'],$ArrFiles)){
                $f_main = 0;
                if ($maxFileName == $f['name'])
                    $f_main = 1;

                try {
                    $d = new DB\Table\NumberCameraImage();
                    $d
                        ->set($d::id_event,$id_event)
                        ->set($d::f_main,$f_main)
                        ->set($d::filename,$f['name'])
                        ->set($d::img,getImage($f['tmp_name'],$licensePlate),true)
                        ->insert();

                }catch (PDOException $e){
                    models\ErrorLog::saveError($e,"apiCameraEvent.txt");
                }

            }
        }
        // Запуск фоном
        $dir = \Properties\Security::DIR();
        $command = "php $dir/subroutine_uploadEventCamera.php > /dev/null &";
        exec($command);
    }

}


function getImage($PathName,$number)
{
    $compression = true;

    if ($number == 'UNKNOWN')
        $compression = false;
    if (filesize($PathName) < 100000)
        $compression = false;

    if ($compression) {
        try {
            $image = new \Imagick();
            $image->readImage($PathName);
            $image->setImageFormat('jpeg');
            $image->setImageCompression(\Imagick::COMPRESSION_JPEG);

            $image->setImageCompressionQuality(20);

            $image->resizeImage( $image->getImageWidth(),$image->getImageHeight(), \Imagick::FILTER_UNDEFINED, 1, true);
            $data = $image->getImageBlob();
        } catch (\Exception $e) {
            $data = "";
        }
    }else{
        $data = file_get_contents($PathName);
    }

    return $data;
}