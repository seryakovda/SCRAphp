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
use DB\Table\nXms_Excel;
use DB\Table\pList;
use DB\Table\pList_TMP;
use DB\Table\pMark;
use DB\Table\pMark_TMP;
use DB\Table\security_userSettings;
use Properties\Security;
use views\mPrint;

class RefreshDataFormPS
{

    public function curl_request_async($array)
    {
        $d =  new ConnectionSettings();
        $data = $d->select()->fetch();
        $ip = $data[$d::addressPS];
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


    private function getExcel()
    {
        $jsonData = $this->curl_request_async(Array(
            "r0"=>"SYS",
            "r1"=>"getExcel",
            "sessionHandle"=>$_SESSION['sessionHandle'],
        ));
        $data = json_decode($jsonData,true);
        $d = new nXms_Excel();

        foreach ($data as $row){
            $d->where($d::ip,$row[$d::ip])->delete();

            foreach ($row as $field => $value){
                $d->set($field,$value);
            }
            $d->insert();
        }

    }
}