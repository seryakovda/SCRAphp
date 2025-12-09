<?php

use DB\Table\ManagerUploadEventCamera;
use \views\mPrint;
require "Properties/error_reporting_DEV.php";
require "spl_autoload.php";

//////////////////////////////////////////////////////////////////////
$TOP = 10; // количество последних записей которые остаются на ubuntu
//////////////////////////////////////////////////////////////////////

mPrint::R("Start",mPrint::GREEN);
// поределяем не работает ли какая другая паралельная задача
$d0 = new ManagerUploadEventCamera();
$dataD0 = $d0->select();
if ($res = $dataD0->fetch()){
    if ($res[$d0::job] == '0'){
        $job = true;
        $iJob = 1;
    }else{
        // если занят то проверяем как долго с последнего запроса
        $start_date = new DateTime($res[$d0::timeJob]);
        $since_start = $start_date->diff(new DateTime());
        if ($since_start->i > 3) {
            $job = true; // если время на запрос превысило 3 минуты то занимаем приоритетную позицию
            $iJob = (int) $res[$d0::job];
            $iJob ++;
        }
        else
            $job = false;
    }
}else{
    $iJob = 1;
    $job = true;
    $d0
        ->set($d0::job,$iJob)
        ->set($d0::timeJob, date ("Y-m-d H:i:s"))
        ->insert();
}


if ($job === false){
    mPrint::R("job === false",mPrint::RED);
    return false;
}
mPrint::R("job === true iJob = $iJob",mPrint::GREEN);


$d0 ->set($d0::job,$iJob)
    ->set($d0::timeJob, date ("Y-m-d H:i:s"))
    ->update();


while ($job){ // крутимся пока всё не обработаем
    // каждую итерацию проверяем в своеё ли мы рабоей еденицы $iJob
    $dataArrD0 = $d0->select()->fetch();
    if ($dataArrD0[$d0::job] != $iJob)
        return false; // если не в своей значит чтото сильно долго работало

    $d0 ->set($d0::timeJob, date ("Y-m-d H:i:s"))
        ->update();

    // получаем максимальный и мимнимальный ключ в списке событий
    $d1 = new \DB\Connect();
    $maxId = $d1->complexQuery("SELECT Max(id) as max_id from DSSL_EventNumberCamera ")->fetchField("max_id");
    $minId = $d1->complexQuery("SELECT Min(id) as min_id from DSSL_EventNumberCamera where f_upload = 0")->fetchField("min_id");

    $d1->complexQuery("
        DELETE FROM DSSL_EventNumberCamera 
        WHERE id IN (
            SELECT id FROM (
                SELECT id FROM DSSL_EventNumberCamera 
                WHERE id < $maxId - $TOP AND f_upload = 1
            ) AS temp
        )
    ");
    // инициализируем масив отправки
    $arraySend = Array();

    $d2 = new \DB\Table\DSSL_EventNumberCamera();
    $data2 = $d2
        ->where($d2::f_upload , "0")
        ->where($d2::id,$minId)
        ->select();
    ///////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////
    if ($dataArr2 = $data2->fetch()){ //если найдена запись
        unset($dataArr2[$d2::f_upload]);
        unset($dataArr2[$d2::id]);
        // инициализируем массив заголовка
        $arrayHead = Array();
        foreach($dataArr2 as $key => $value){
            $arrayHead[$key] = $value;
        }

        $d3 = new \DB\Table\NumberCameraImage();

        $dataD3 = $d3->where($d3::id_event,$minId)->select();
        $lisFiles = Array();
        while ($dataArrD3 = $dataD3->fetch()){
            $fileContent = $dataArrD3[$d3::img]; // бинарные данные из БД
            $filename = $dataArrD3[$d3::id];         // имя, под которым хотим отправить
            $mime = "aimage/jpeg";
            $file = new \CURLStringFile($fileContent, $mime, $filename);
            $arraySend["file_".$dataArrD3[$d3::id]] = $file;

            $lisFiles["file_".$dataArrD3[$d3::id]] = Array (
                $d3::filename => $dataArrD3[$d3::filename],
                $d3::f_main => $dataArrD3[$d3::f_main]
            );

        }
        $arrayHead['lisFiles'] = $lisFiles;
        $arraySend['head'] = json_encode($arrayHead,true);

        $R = new models\RefreshDataFormPS();
        if ($R->testConnection()){
            $R->getSession();
            if ($R->authorizationOnThePS()){
                $res = $R->sendEventCamera($arraySend);
                if ($res == "OK"){
                    mPrint::R("OK",mPrint::YELLOW);
                    if ($minId + $TOP < $maxId){
                        $d2 ->where($d2::id,$minId)
                            ->delete();
                    }else{
                        $d2 ->set($d2::f_upload , "1")
                            ->where($d2::id,$minId)
                            ->update();
                    }

                }else{
                    mPrint::R($res,mPrint::RED);

                }

            }
        }
    }else{ // если ни одно йзаписи ненайдено
        $job = false;
        $d0 ->set($d0::job,"0")
            ->set($d0::timeJob, date ("Y-m-d H:i:s"))
            ->update();
    }

    mPrint::R("END",mPrint::GREEN);
}
// чистим фотографии
$d1->complexQuery("
        DELETE FROM NumberCameraImage 
            WHERE id IN (
                SELECT id FROM (
                    SELECT NumberCameraImage.id
                    FROM NumberCameraImage 
                    LEFT OUTER JOIN DSSL_EventNumberCamera 
                        ON NumberCameraImage.id_event = DSSL_EventNumberCamera.id
                    WHERE DSSL_EventNumberCamera.id IS NULL
                ) AS temp
            )
    ");

