<?php

namespace forms\SkeletonApp;

use DB\Connect;
use DB\Connection;
use DB\Table\ConnectionSettings;
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
        $user = User::get();
        return $user->testConnectOrion();
    }
}