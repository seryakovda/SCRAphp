<?php

namespace forms\SkeletonApp;

use DB\Connection;
use DB\Table\ConnectionSettings;
use models\_G_session;

class Control extends \forms\FormsControl
{
    function __construct()
    {
        $this->VIEW = new VIEW();
        $this->MODEL = new MODEL();
//        $this->MODEL->clearSession();
        parent::__construct();
        $_SESSION["mobile"] = array_key_exists("mobile",  $_SESSION) ? $_SESSION["mobile"]  :'workstation';
        //array_key_exists("mobile",  $_SESSION)
    }


    public function defaultMethod()
    {
        $user = \models\User::get();
        //$menuDate = $user->getMenu();
        //$this->VIEW->setMenuDate($menuDate);
        if ($user->data['admin'] == 1) {
            $this->VIEW->setMODEL($this->MODEL);
            $this->VIEW->SkeletonApplication();
        }
    }

    public function logOut()
    {
        $this->MODEL->logOut();
    }

    public function replaceValue()
    {
        \models\ErrorLog::saveError($_REQUEST);
        $d = new ConnectionSettings();
        $d->set($_REQUEST['_var'],$_REQUEST['_val'])
            ->update();
    }

    public function testConnectOrion()
    {
        if ($res = $this->MODEL->testConnectOrion()){
            print "Тест проверки пройден. ". $res;
        }else{
            print "Тест проверки ПРОВАЛЕН !!!";
        }
    }
}
