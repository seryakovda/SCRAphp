<?php


namespace models;


use DB\Connect;
use DB\Connection;
use DB\Table\ConnectionSettings;
use DB\Table\AcessPoint;
use DB\Table\AcessPoint_TMP;
use DB\Table\GrAccess;
use DB\Table\GrAccess_TMP;

use DB\Table\LastId;
use DB\Table\pList;
use DB\Table\pList_TMP;
use DB\Table\pMark;
use DB\Table\pMark_TMP;
use Properties\Security;
use views\mPrint;

class RefreshDataFormOrion
{
    public $ConnOrion = Array();

    public $lastID = 0;

    public function __construct()
    {
        $d =  new ConnectionSettings();
        $data = $d->select()->fetch();
        $this->ConnOrion = Array();
        $this->ConnOrion["MSSQL"] =  Security::TYPE_dB_MS_SQL;
        $this->ConnOrion["serverName"] =  $data[$d::address_DbOrion];
        $this->ConnOrion["dataBase"] =    $data[$d::db_DbOrion];
        $this->ConnOrion["userName"] =    $data[$d::login_DbOrion];
        $this->ConnOrion["password"] =    $data[$d::pass_DbOrion];
    }

    public function testConnectOrion()
    {

        $connOrion = new Connect($this->ConnOrion);
        $ret = true;
        try{
            $data = $connOrion->complexQuery('Select @@version as ver');
            if ($res = $data->fetch()){
                $ret = $res['ver'];
            }else{
                $ret = false;
            }
        }catch (\PDOException $e){
            $ret = false;
        }

        return $ret;
    }


    public function getMAxIdFor_TRIGGER_SPR()
    {
        $connMSSQL = new Connect($this->ConnOrion);
        return $connMSSQL->complexQuery('select MAX(id) as maxId from TRIGGER_SPR')->fetchField('maxId');
    }

    public function getMAxIdFor_TRIGGER_pList()
    {
        $connMSSQL = new Connect($this->ConnOrion);
        return $connMSSQL->complexQuery('select MAX(id) as maxId from TRIGGER_pList_UPD')->fetchField('maxId');
    }

    public function getMAxIdFor_TRIGGER_pMark()
    {
        $connMSSQL = new Connect($this->ConnOrion);
        return $connMSSQL->complexQuery('select MAX(id) as maxId from TRIGGER_pMark_UPD')->fetchField('maxId');
    }


    private function getListField($classTable)
    {
        $oClass = new \ReflectionClass("\DB\Table\\$classTable");
        $listFieldArr = $oClass->getConstants();
        unset ($listFieldArr['Uid']);
        $listField = "";
        foreach ($listFieldArr as $field){
            $listField = $listField . $field . ",";
        }
        $listField = substr($listField ,0 , -1);
        return $listField;

    }


    public function updateSPR($nameTable)
    {
        $ClassNameTable = "\DB\Table\\$nameTable";
        $nameTable_TMP = $nameTable."_TMP";
        $ClassNameTable_TMP = "\DB\Table\\$nameTable_TMP";
        $listField = $this->getListField($nameTable);
        $d = new $ClassNameTable_TMP(); // временная таблица для получения данных с сервера Orion
        $d->delete();


        $connMSSQL = new Connect($this->ConnOrion);
        $data = $connMSSQL->complexQuery("select $listField from $nameTable");
        while ($res = $data->fetch()){
            foreach ($res as $field => $value){
                $d->set($field , $value);
            }
            $d->insert();
        }
        $d1 = new $ClassNameTable();
        $d1->delete();
        $conn = new Connect();
        $conn->complexQuery("insert into $nameTable ($listField) select $listField from $nameTable_TMP");
    }

    public function getFull_pList_start()
    {
        $LastId = new LastId();
        $LastId->set($LastId::f_pList,"0")->update(); //если флаг 1 то нужно продолжать работу а ноль начать заново

        $d = new pList_TMP();
        $d->delete();
        $exit = false;
        while (!$exit){
            try {
                $exit = true;
                $this->getFull_pList_next();
            }catch (\PDOException $e){
                $exit = false;
                mPrint::R("Сбой",mPrint::RED);
            }
        }



    }

    public function getFull_pList_next()
    {
        $LastId = new LastId();
        $data = $LastId->select($LastId::f_pList . ','  .$LastId::id_pList)->fetch();
        if ($data[$LastId::f_pList] == "1"){
            $startId = $data[$LastId::id_pList];
        }else{
            $startId = 0;
            $LastId->set($LastId::f_pList,"1")->update(); //если флаг 1 то нужно продолжать работу а ноль начать заново
        }
        $connMSSQL = new Connect($this->ConnOrion);

        $query1 = $this->buildQuery_pList("> $startId");
        mPrint::R('start',mPrint::LIGHT_BLUE);

        $data1 = $connMSSQL->complexQuery($query1)->fetchAll();
        //mPrint::R($data1,mPrint::YELLOW);

        $ID = 0;
        foreach ($data1 as $key => $row){
            $d = new pList_TMP();
            foreach ($row as $field => $value){
                $binary = false;
                if ($field == 'Picture')
                    $binary = true;
                if ($field == 'ID')
                    $ID = $value;
                $d->set($field,$value,$binary);
            }
            $d->insert();
            $d = null;
            unset($d);
            $LastId->set($LastId::id_pList,$ID)->update();
        }
        $LastId = null;
        unset($LastId);

        if ($ID != 0) {
            mPrint::R($ID,mPrint::GREEN);
            $this->getFull_pList_next();
        }else
            $this->getFull_pList_end();
    }

    public function getFull_pList_end()
    {
        $conn = new Connect();
        $kol_row = $conn->complexQuery("select SUM(1) as kol_row from pList_TMP") ->fetchField('kol_row');
        if ($kol_row > 0) {
            $d = new pList();
            $d->delete();
            mPrint::R('delete pList',mPrint::RED);
            $conn = new Connect();
            mPrint::R('startAdd',mPrint::GREEN);
            $conn->complexQuery("
                insert into pList (ID,	Name,	FirstName,	MidName,	CompN,	DivN,	name_pPost,	Picture)
                select ID,	Name,	FirstName,	MidName,	CompN,	DivN,	name_pPost,	Picture 
                from pList_TMP
                ");
            mPrint::R('EndAdd',mPrint::GREEN);
            $d = new pList_TMP();
            mPrint::R('StartDelete_TMP',mPrint::GREEN);
            $d->delete();

            mPrint::R('EndDelete_TMP',mPrint::GREEN);

            $LastId = new LastId();
            $LastId->set($LastId::f_pList,"0")->update(); //если флаг 1 то нужно продолжать работу а ноль начать заново
        }
        mPrint::R('STOP',mPrint::PINK);
    }



    public function getFull_pMark_start()
    {
        $LastId = new LastId();
        $LastId->set($LastId::f_pMark,"0")->update(); //если флаг 1 то нужно продолжать работу а ноль начать заново

        $d = new pMark_TMP();
        $d->delete();
        $this->getFull_pMark_next();
        $this->lastID = $this->getMAxIdFor_TRIGGER_pMark();
    }

    public function getFull_pMark_next()
    {
        $LastId = new LastId();
        $data = $LastId->select($LastId::f_pMark . ',' . $LastId::id_pMark)->fetch();
        if ($data[$LastId::f_pMark] == "1"){
            $startId = $data[$LastId::id_pMark];
        }else{
            $startId = 0;
            $LastId->set($LastId::f_pMark,"1")->update(); //если флаг 1 то нужно продолжать работу а ноль начать заново
        }
        $connMSSQL = new Connect($this->ConnOrion);
        $query1 = $this->buildQuery_pMark("> $startId");

        mPrint::R('start',mPrint::LIGHT_BLUE);
        $data1 = $connMSSQL->complexQuery($query1)->fetchAll();

        $ID = 0;
        foreach ($data1 as $key => $row){
            $d = new pMark_TMP();
            foreach ($row as $field => $value){
                if ($field == 'ID')
                    $ID = $value;
                $d->set($field,$value);
            }
            $d->insert();
            $d = null;
            unset($d);
            $LastId->set($LastId::id_pMark,$ID)->update();
//            mPrint::R($ID,mPrint::BLUE);
        }
        if ($ID != 0) {
            mPrint::R($ID,mPrint::GREEN);
            $this->getFull_pMark_next();
        }else
            $this->getFull_pMark_end();
    }



    public function getFull_pMark_end()
    {
        $conn = new Connect();
        $kol_row = $conn->complexQuery("select SUM(1) as kol_row from pMark_TMP") ->fetchField('kol_row');
        if ($kol_row > 0){
            $d = new pMark();
            $d->delete();
            mPrint::R('delete pMark',mPrint::RED);
            mPrint::R('startAdd',mPrint::GREEN);
            $conn->complexQuery("
                insert into pMark ( ID, Gtype, Config, CodeP_HEX, Status, Owner, GroupID, Start, Finish)
                    select ID, Gtype, Config, CodeP_HEX, Status, Owner, GroupID, Start, Finish 
                    from pMark_TMP
                ");
            mPrint::R('EndAdd',mPrint::GREEN);
            $d = new pMark_TMP();
            mPrint::R('StartDelete_TMP',mPrint::GREEN);
            $d->delete();

            mPrint::R('EndDelete_TMP',mPrint::GREEN);

            $LastId = new LastId();
            $LastId
                ->set($LastId::f_pMark,"0")
                ->set($LastId::id_pMark,$this->lastID)
                ->update(); //если флаг 1 то нужно продолжать работу а ноль начать заново
        }

        mPrint::R('STOP',mPrint::PINK);
    }

    private function buildQuery_pMark($condition)
    {
        return "SELECT        TOP (100) 
                                   ID, 
                                   Gtype, 
                                   Config, 
                                   dbo.fn_convert_codeP(CodeP) AS CodeP_HEX, 
                                   Status, 
                                   Owner, 
                                   GroupID, 
                                   Start, 
                                   Finish
           FROM            
                pMark
           WHERE 
                 dbo.pMark.ID  $condition
           ORDER BY  dbo.pMark.ID";
    }


    private function buildQuery_pList($condition)
    {
        return "SELECT        TOP (100) 
                                dbo.pList.ID,
                                dbo.pList.Name,
                                dbo.pList.FirstName,
                                dbo.pList.MidName,
                                dbo.PCompany.Name AS CompN,
                                dbo.PDivision.Name AS DivN,
                                dbo.PPost.Name AS name_pPost,
                                dbo.pList.Picture
        FROM            
             dbo.pList 
        LEFT OUTER JOIN
                 dbo.PDivision 
                     ON 
                         dbo.pList.Section = dbo.PDivision.ID 
        LEFT OUTER JOIN
                 dbo.PCompany 
                     ON 
                         dbo.pList.Company = dbo.PCompany.ID 
        LEFT OUTER JOIN
                 dbo.PPost 
                     ON 
                         dbo.pList.Post = dbo.PPost.ID 
        WHERE dbo.pList.ID $condition
        ORDER BY  dbo.pList.ID";
    }

    public function refresh_pMark_afterLastId()
    {
        $d0 = new LastId();
        $data = $d0 ->select ("{$d0::id_pMark},{$d0::f_pMark}") -> fetch();
        if ($data[$d0::f_pMark] == 0){ // если идёт обновление встревать ненужно
            $lastID = $data[$d0::id_pMark];
            $lastID_inDB = $this->getMAxIdFor_TRIGGER_pMark(); // последнй ID триггера

            $connMSSQL = new Connect($this->ConnOrion);
            $condition = "
            SELECT        id_Table
            FROM          TRIGGER_pMark_UPD
            WHERE        (id > $lastID)
            GROUP BY id_Table
            HAVING        (NOT (id_Table IS NULL))";
            $condition = "IN ($condition)";
            $query1  = $this->buildQuery_pMark($condition);
            $data1 = $connMSSQL->complexQuery($query1)->fetchAll();

            $ID = 0;
            foreach ($data1 as $key => $row){
                $data = $d0 ->select ("{$d0::id_pMark},{$d0::f_pMark}") -> fetch();
                if ($data[$d0::f_pMark] != 0){ //если вдруг начато глобальное обновление вырубаемся
                    break;
                }
                $d = new pMark();// берём таблицу
                foreach ($row as $field => $value){ // заполняем поля
                    if ($field == 'ID')
                        $ID = $value; // получаем ID
                    $d->set($field,$value);
                }
                $d1 = new pMark(); // выбираем туже таблицу
                $d1->where($d1::ID,$ID)->delete(); // удаляем старое значение (если оно есть)

                $d->insert(); // добавляю вновь пришедшее
                $d = null;
                unset($d);
            }
            if ($data[$d0::f_pMark] != 0){ //если вдруг начато глобальное обновление вырубаемся
                return ;
            }
            $d0->set($d0::id_pMark,$lastID_inDB)->update(); // запоминаем последний обработанный ID для следующего фрагментированного  обновления
        }
    }

    public function refresh_pList_afterLastId()
    {
        $d0 = new LastId();
        $data = $d0 ->select ("{$d0::id_pList},{$d0::f_pList}") -> fetch();
        if ($data[$d0::f_pList] == 0){ // если идёт обновление встревать ненужно
            $lastID = $data[$d0::id_pList];
            $lastID_inDB = $this->getMAxIdFor_TRIGGER_pList(); // последнй ID триггера
            $connMSSQL = new Connect($this->ConnOrion);
            $condition = "
            SELECT        id_Table
            FROM          TRIGGER_pList_UPD
            WHERE        (id > $lastID)
            GROUP BY id_Table
            HAVING        (NOT (id_Table IS NULL))";
            $condition = "IN ($condition)";
            $query1  = $this->buildQuery_pList($condition);
            $data1 = $connMSSQL->complexQuery($query1)->fetchAll();

            $ID = 0;
            foreach ($data1 as $key => $row){
                $data = $d0 ->select ("{$d0::id_pList},{$d0::f_pList}") -> fetch();
                if ($data[$d0::f_pList] != 0){ //если вдруг начато глобальное обновление вырубаемся
                    break;
                }

                $d = new pList();// берём таблицу
                foreach ($row as $field => $value){ // заполняем поля
                    if ($field == 'ID')
                        $ID = $value; // получаем ID
                    $d->set($field,$value);
                }
                $d1 = new pList(); // выбираем туже таблицу
                $d1->where($d1::ID,$ID)->delete(); // удаляем старое значение (если оно есть)

                $d->insert(); // добавляю вновь пришедшее
                $d = null;
                unset($d);
            }
            if ($data[$d0::f_pList] != 0){ //если вдруг начато глобальное обновление вырубаемся
                return ;
            }
            $d0->set($d0::id_pList,$lastID_inDB)->update(); // запоминаем последний обработанный ID для следующего фрагментированного  обновления
        }
    }
}