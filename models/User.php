<?php

namespace models;

use DB\Connect;
use DB\Connection;
use DB\Table\ConnectionSettings;
use DB\Table\nXms_Excel;
use DB\Table\security_userSettings;
use Properties\Security;


class User
{
    private static $_object;

    public $id;
    public $surnameAndInitials;
    public $data;
    private $conn;

    function __construct()
    {

    }

    public static function get()
    {
        if (!isset(self::$_object)) {
            self::$_object = new self;
        }
        return self::$_object;

    }

    public function login($login, $password)
    {
        $login = mb_strtolower($login, 'UTF-8');
        $browser = $_SERVER['HTTP_USER_AGENT'];
        _G_session::userPassword($password);

        //проверяем правильность ввода логина и пароля
        $password = $this->hashPassword($password);
        $users = new \DB\Table\Users();
        $res =$users
            ->where($users::login, $login)
            ->where($users::password_crypto, $password)
            ->select();

        if ($this->data = $res->fetch()) {
            $this->id = $this->data[$users::id];
            $this->createFullName();


            return $this->id;
        } else {
            return false;
        }
    }



    public function hashPassword($password)
    {
        $final = unpack("H*hex",$password);
        return hash ('ripemd256',$final['hex']);
    }


    private function createFullName()
    {
        //$this->surnameAndInitials = $this->data['surname'] . ' ' . mb_substr($this->data['name'], 0, 1) . '.' . mb_substr($this->data['patronName'], 0, 1) . '.';
    }



    public function find($id)
    {
        $users = new \DB\View\View_Users();
        $res = $users->where($users::id, $id)->select();
        if ($this->data = $res->fetch()) {
            $this->id = $this->data[$users::id];
            $this->createFullName();
            $this->getUserSettingsFromDB();
            return true;
        } else {
            return false;
        }

    }

    public function updateIdSession($id)
    {
        $user = new \DB\Table\Users();
        $user
            ->set($user::session_id,session_id())
            ->where($user::id,$id)
            ->update();
    }



    public function ReplacePassword($Password1,$id_user,$renewPassword)
    {
        $user = \models\User::get();
        $password = $Password1;
        $password_crypto = $user->hashPassword($password);
        $user = new \DB\Table\Users();

        $user
            ->set($user::renewPassword, $renewPassword)
            ->set($user::password_crypto,$password_crypto )
            ->where($user::id,$id_user)
            ->update();
    }

    public function testConnectOrion()
    {
        $d =  new ConnectionSettings();
        $data = $d->select()->fetch();
        $arrayConnectionSettings = Array();
        $arrayConnectionSettings["MSSQL"] =  Security::TYPE_dB_MS_SQL;
        $arrayConnectionSettings["serverName"] =  $data[$d::address_DbOrion];
        $arrayConnectionSettings["dataBase"] =    $data[$d::db_DbOrion];
        $arrayConnectionSettings["userName"] =    $data[$d::login_DbOrion];
        $arrayConnectionSettings["password"] =    $data[$d::pass_DbOrion];

        $connOrion = new Connect($arrayConnectionSettings);
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
    private function getUserSettingsFromDB()
    {
        $this->getSettingsFromPass_system();

        $d = new \DB\Table\security_userSettings();
        $data = $d->where($d::id_user,$this->id)
            ->select();
        while ($res = $data->fetch()){
            $this->data[$res[$d::nameVar]] = $res[$d::value];
        }
    }

    private function getSettings()
    {
        $jsonData = $this->curl_request_async(Array(
            "r0"=>"SYS",
            "r1"=>"getSettings",
            "sessionHandle"=>$_SESSION['sessionHandle'],
        ));
        $data = json_decode($jsonData,true);
        \models\ErrorLog::saveError($data);
        $d = new security_userSettings();
        $d->where($d::id_user,_G_session::id_user())->delete();

        foreach ($data as $row){
            $d->set($d::id_user,_G_session::id_user());
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

    public function getSettingsFromPass_system()
    {
        if ($this->testConnectOrion() !== false){
            $this->getSession();
            if ($this->authorizationOnThePS()){
                if (_G_session::id_user() != 0)
                    if (array_key_exists('sateGetDate',$_SESSION) === false){
                        $this->getSettings();
                        $this->getExcel();
                        $_SESSION['sateGetDate'] = 1;
                    }
            }
        }
    }
}