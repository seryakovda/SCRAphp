<?php

use DB\Connection;
use DB\Table\ConnectionSettings;
use DB\Table\ManagerUploadReadKey;
use DB\Table\Orion_regKey;
use Properties\Security;
use \views\mPrint;

require "Properties/error_reporting_DEV.php";
require "spl_autoload.php";


//////////////////////////////////////////////////////////////////////
$TOP = 10; // количество последних записей которые остаются на ubuntu
//////////////////////////////////////////////////////////////////////

mPrint::R("Start",mPrint::GREEN);

$d0 = new ManagerUploadReadKey();
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

$d =  new ConnectionSettings();
$data = $d->select()->fetch();
$ConnOrion = Array();
$ConnOrion["MSSQL"] =       Security::TYPE_dB_MS_SQL;
$ConnOrion["serverName"] =  $data[$d::address_DbOrion];
$ConnOrion["dataBase"] =    $data[$d::db_DbOrion];
$ConnOrion["userName"] =    $data[$d::login_DbOrion];
$ConnOrion["password"] =    $data[$d::pass_DbOrion];

while ($job) { // крутимся пока всё не обработаем
    // каждую итерацию проверяем в своеё ли мы рабоей еденицы $iJob
    $dataArrD0 = $d0->select()->fetch();
    if ($dataArrD0[$d0::job] != $iJob)
        return false; // если не в своей значит чтото сильно долго работало

    $d0->set($d0::timeJob, date("Y-m-d H:i:s"))
        ->update();

//    $d1 = new \DB\Connect();
//    $maxId = $d1->complexQuery("SELECT Max(id) as max_id from Orion_regKey ")->fetchField("max_id");
//    $minId = $d1->complexQuery("SELECT Min(id) as min_id from Orion_regKey where f_upload = 0")->fetchField("min_id");
//
//    $d1->complexQuery("
//        DELETE FROM Orion_regKey
//        WHERE id IN (
//            SELECT id FROM (
//                SELECT id FROM Orion_regKey
//                WHERE id < $maxId - $TOP AND f_upload = 1
//            ) AS temp
//        )
//    ");

    $conn = new \DB\Connect();

    // запрос получение необходимых данных для передачи в БД ORION
    $query = " 
        SELECT
          Orion_regKey.dateTimeEvent AS TimeVal,
          Orion_regKey.dateTimeEvent AS DeviceTime,
          ifnull ( pMark.ID ,0) AS ZReserv,
          ifnull ( pMark.Owner,0)  AS HozOrgan,
          Orion_settingsFor_pLogData.RazdIndex,
          Orion_settingsFor_pLogData.IndexZone,
          Orion_settingsFor_pLogData.ReaderIndex,
          Orion_settingsFor_pLogData.DoorIndex,
          Orion_settingsFor_pLogData.Mode,
          Orion_settingsFor_pLogData.ZoneIndex,
          Orion_settingsFor_pLogData.Event,
          CONCAT(
              'HexKey:',
              keyCard,
              ' ',
              IFNULL(pList.Name,''),
              ' ',
              IFNULL(pList.FirstName,''),
              ' ',
              IFNULL(pList.MidName,'')
              ) as Remark,
          Orion_regKey.id
        FROM Orion_regKey
          LEFT OUTER JOIN pMark
            ON Orion_regKey.keyCard = pMark.CodeP_HEX
          LEFT OUTER JOIN pList
            ON pMark.Owner = pList.ID
          INNER JOIN Orion_settingsFor_pLogData
            ON Orion_regKey.inOut_ = Orion_settingsFor_pLogData.id
        WHERE Orion_regKey.f_upload = 0
    ";
    $data2 = $conn->complexQuery($query);


    $job = false; //предполагаем что данные для передачи кончились
    while ($row = $data2->fetch()){
        $job = true; //если есть хотябы одна запись то оставляем шан сн следующий запрос к передаче

        $conn_MSSQL = new \DB\Connect($ConnOrion);
        $conn_MSSQL->table('pLogData');
        foreach ($row as $field => $value){
            if ($field == 'id'){
                $ID = $value; // запоминаем ID
            }else{

                if ( ($field == "TimeVal") || ($field == "DeviceTime") ) {
                    $value = date("d.m.Y H:i:s", strtotime($value));
                }

                $conn_MSSQL->set($field , $value);
            }
        }
        try{
            $conn_MSSQL->insert();
            mPrint::R("OK",mPrint::YELLOW);

            $d3 = new Orion_regKey(); //удаляем запись
            $d3->where($d3::id,$ID)
                ->delete();

        }catch (\PDOException $e){
            mPrint::R("Error",mPrint::RED);
            mPrint::R($e,mPrint::RED);
        }
    }
    if ($job === false){// если ни одно йзаписи ненайдено
        $d0 ->set($d0::job,"0")
            ->set($d0::timeJob, date ("Y-m-d H:i:s"))
            ->update();
    }


    /*
    pMark.ID =              pLogData.ZReserv
    DevItems.GIndex =       pLogData.ReaderIndex // Считыватель 1, Прибор 6
    AcessPoint.GIndex =     pLogData.DoorIndex //

    DevItems.GIndex =       pLogData.IndexZone //
    DevItems.GIndex =       pLogData.ReaderIndex //
    DevItems.DeviceID =     RSLines.ID //
    DevItems.GIndex = NNN // сЧитыватель 1
    DevItems.GIndex = NNN+1 // сЧитыватель 2


    RSLines.GLineNo // номер линиии (23 - КПП-4 Переносной) (6 - Запасной Вход АБК)


     */
}