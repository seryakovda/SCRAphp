<?php


namespace models;


use DB\Connect;
use DB\Connection;
use DB\Table\CarPrivilege;
use DB\Table\ConnectionSettings;
use DB\Table\AcessPoint;
use DB\Table\AcessPoint_TMP;
use DB\Table\GrAccess;
use DB\Table\GrAccess_TMP;

use DB\Table\LastId;
use DB\Table\nXms_Excel;
use DB\Table\Orion_settingsFor_pLogData;
use DB\Table\pList;
use DB\Table\pList_TMP;
use DB\Table\pMark;
use DB\Table\pMark_TMP;
use DB\Table\security_userSettings;
use Properties\Security;
use views\mPrint;
use DB\Table\Human;
use DB\Table\PassFields;
use DB\Table\PassStatus;
use DB\Table\PassTable;
use DB\Table\PassHead;
use DB\Table\Car;

class RefreshDataFormPS
{

    private $data;

    public function __construct()
    {
        $d =  new ConnectionSettings();
        $this->data = $d->select()->fetch();
    }

    public function curl_request_async($array)
    {
        $ip = $this->data[ConnectionSettings::addressPS];
        $HTTP = "http://$ip/index_ajax.php";
        $ch = curl_init($HTTP);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $html = curl_exec($ch);
        curl_close($ch);
        return $html;
    }

    public function testConnection()
    {
        $ip = $this->data[ConnectionSettings::addressPS];
        $HTTP = "http://$ip/index_ajax.php";
        if(!filter_var($HTTP, FILTER_VALIDATE_URL)){
            return false;
        }
        //инициализация curl
        $curlInit = curl_init($HTTP);
        curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($curlInit,CURLOPT_HEADER,true);
        curl_setopt($curlInit,CURLOPT_NOBODY,true);
        curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
        //получение ответа
        $response = curl_exec($curlInit);
        curl_close($curlInit);
        if ($response) return true;
        return false;
    }


    public function getSession()
    {
        $jsonData = $this->curl_request_async(Array(
            "r0"=>"SYS",
            "r1"=>"registrationKeyAPI",
            "keyAPI"=>Security::keyAPI
        ));
        $data = json_decode($jsonData,true);
        if (array_key_exists('sessionHandle',$data)){
            $_SESSION['sessionHandle'] = $data['sessionHandle'];
        }else{
            if (array_key_exists('sessionHandle',$_SESSION)){
                unset($_SESSION['sessionHandle']);
            }
        }
    }

    public function authorizationOnThePS()
    {
        $d =  new ConnectionSettings();
        $data = $d->select()->fetch();
        $login = $data[$d::loginPS];
        $password = $data[$d::passPS];

        $jsonData = $this->curl_request_async(Array(
            "r0"=>"SYS",
            "r1"=>"autorisation",
            "login"=>$login,
            "pass"=>$password,
            "sessionHandle"=>$_SESSION['sessionHandle'],
        ));
        $data = json_decode($jsonData,true);
        if (array_key_exists('state',$data)){
            if ($data['state'] == "true")
                return true;
            else
                return false;
        }else{
            return false;
        }
    }

    public function getSettings()
    {
        $jsonData = $this->curl_request_async(Array(
            "r0"=>"SYS",
            "r1"=>"getSettings",
            "sessionHandle"=>$_SESSION['sessionHandle'],
        ));
        $data = json_decode($jsonData,true);
        $d = new security_userSettings();
        $d->where($d::id_user,_G_session::id_user())->delete();

        foreach ($data as $row){
            $id_user = _G_session::id_user();
            $d->set($d::id_user,$id_user);
            unset($row['id']);
            unset($row['id_user']);
            foreach ($row as $field => $value){
                $d->set($field,$value);
            }
            $d->insert();
        }

    }

    public function prepare_Orion_settingsFor_pLogData()
    {
        $d = new security_userSettings();
        $data = $d->select();
        $retD = Array();
        while ($row = $data->fetch()){
            $retD[$row[$d::nameVar]] = $row[$d::value];
        }

        $d1 = new Orion_settingsFor_pLogData();
        $d1->delete();
        $d1
            ->set($d1::id,0)// выход
            ->set($d1::DoorIndex,$retD['orionReader_DoorIndex'])
            ->set($d1::ZoneIndex,$retD['orionReader_ZoneIndex'])

            ->set($d1::ReaderIndex,$retD['orionReader_DevItems_1'])
            ->set($d1::IndexZone,$retD['orionReader_DevItems_1'])

            ->set($d1::RazdIndex,"0")
            ->set($d1::Mode,2)
            ->set($d1::Event,28)
            ->insert();
        $d1
            ->set($d1::id,1)// выход
            ->set($d1::DoorIndex,$retD['orionReader_DoorIndex'])
            ->set($d1::ZoneIndex,$retD['orionReader_ZoneIndex'])

            ->set($d1::ReaderIndex,$retD['orionReader_DevItems_2'])
            ->set($d1::IndexZone,$retD['orionReader_DevItems_2'])

            ->set($d1::RazdIndex,"0")
            ->set($d1::Mode,1)
            ->set($d1::Event,28)
            ->insert();

    }

    public function getExcel()
    {
        $jsonData = $this->curl_request_async(Array(
            "r0"=>"SYS",
            "r1"=>"getExcel",
            "sessionHandle"=>$_SESSION['sessionHandle'],
        ));
        $data = json_decode($jsonData,true);

        foreach ($data as $row){
            $d = new nXms_Excel();
            $d->where($d::ip,$row[$d::ip])->delete();
            $d = null;

            $d = new nXms_Excel();
            foreach ($row as $field => $value){
                $d->set($field,$value);
            }
            $d->insert();
        }

    }

    public function getCarPrivilege()
    {
        $jsonData = $this->curl_request_async(Array(
            "r0"=>"SYS",
            "r1"=>"getCarPrivilege",
            "sessionHandle"=>$_SESSION['sessionHandle'],
        ));
        $data = json_decode($jsonData,true);

        foreach ($data as $row){
            $d = new CarPrivilege();
            $d->where($d::stateNumber,$row[$d::stateNumber])->delete();
            $d = null;

            $d = new CarPrivilege();
            foreach ($row as $field => $value){
                $d->set($field,$value);
            }
            $d->insert();
        }
    }

    /**
     * @param $nameTable string имя талицы БД PassFields,PassHead и т.д ВАЖНО должно содержать id
     * @return false|mixed
     */
    public function getDataTable($nameTable)
    {
        $conn = new Connect();
        // получаем последний Id который имеем в нашей базе
        $lastId = $conn->complexQuery("select ifnull(max(id),0) as lastId from $nameTable")->fetchField("lastId");

        $nameTable = "\DB\Table\\$nameTable";

        $jsonData = $this->curl_request_async(Array(
            "r0"=>"SYS",
            "r1"=>"getDataTable",
            "sessionHandle"=>$_SESSION['sessionHandle'],
            "nameTable"=>$nameTable,
            "lastId"=>$lastId,
        ));
        $data = json_decode($jsonData,true);
        mPrint::R("Статус ".$data['status'],mPrint::YELLOW);
        if ($data['status'] == "Job"){
            foreach ($data['data'] as $row){
                $d = new $nameTable();
                foreach ($row as $field => $value){
                    if ($field == 'id')
                        $retId = $value;
                    $d->set($field,$value);
                }
                try{ // вдруг данные уже есть
                    $d->insert();
                    mPrint::R("|",mPrint::GREEN,false);
                }catch (\PDOException $e){
                    mPrint::R("Ошибка записи id= $retId таблицы $nameTable", mPrint::GREEN);
                }
            }
            mPrint::R("ОК",mPrint::BLUE);
            return $retId;
        }else{
            return false;
        }

    }

    public function sendEventCamera($sendArray)
    {
        $newSendArray = Array(
            "r0"=>"SYS",
            "r1"=>"sendEventCamera",
            "sessionHandle"=>$_SESSION['sessionHandle'],
        );
        foreach($sendArray as $key => $value){
            $newSendArray[$key] = $value;
        }
        $jsonData = $this->curl_request_async($newSendArray);

        return $jsonData;
    }
}