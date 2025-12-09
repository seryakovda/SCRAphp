<?php

namespace models;

use DB\Connect;
use DB\Connection;
use DB\Table\ConnectionSettings;
use DB\Table\nXms_Excel;
use DB\Table\security_userSettings;
use DB\Table\Users;
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
        $data   = hash ('ripemd256',$final['hex']);
        return $data;
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

    private function getUserSettingsFromDB()
    {

        if ($this->data[users::admin] != '1')
            $this->getSettingsFromPass_system();

        $d = new \DB\Table\security_userSettings();
        $data = $d->where($d::id_user,$this->id)
            ->select();
        while ($res = $data->fetch()){
            $this->data[$res[$d::nameVar]] = $res[$d::value];
        }
    }


    public function getSettingsFromPass_system()
    {
        $RD = new RefreshDataFormPS();
        if ($RD->testConnection()){
            $RD->getSession();
            if ($RD->authorizationOnThePS()){
                if (_G_session::id_user() != 0)
                    if (array_key_exists('sateGetDate',$_SESSION) === false){
                        $RD->getSettings();
                        $_SESSION['sateGetDate'] = 1;
                        $RD->prepare_Orion_settingsFor_pLogData(); //подготовка таблицы для записи в pLogData
                    }
            }

        }
    }
}