<?php

namespace forms\SkeletonApp;

use DB\Connect;
use DB\Connection;
use DB\Table\ConnectionSettings;
use models\RefreshDataFormOrion;
use models\RefreshDataFormPS;
use Properties\Security;
use models\User;

class MODEL
{
    public function logOut()
    {
        foreach ($_SESSION as $key => $value){
            unset($_SESSION[$key]);
        }
    }
    public function getData()
    {
        $d = new ConnectionSettings();
        return $d->select()->fetch();
    }

    public function testConnectOrion()
    {
        $RD = new RefreshDataFormOrion();
        return $RD->testConnectOrion();
    }

    public function testConnectPS()
    {
        $RD = new RefreshDataFormPS();
        return $RD->testConnection();
    }
}