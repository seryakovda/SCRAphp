<?php

use DB\Proc\Proc_TriggerNumberCamera;
use views\mPrint;

session_start();
require "Properties/error_reporting.php";
require "spl_autoload.php";

if (count($argv) == 1){
    mPrint::R("Необходим параметр Clear|NoClear");
    return;
}

$R = new models\RefreshDataFormPS();
if ($R->testConnection()){
    $R->getSession();
    if ($R->authorizationOnThePS()){

        if ($argv[1] == 'Clear') {
            $d = new \DB\Table\Human();
            $d->where($d::id, 0, ">=")->delete();
        }
        while ($id =  $R->getDataTable(\DB\Table\Human::getName())){
            mPrint::R($id,mPrint::PINK);
        }

        if ($argv[1] == 'Clear') {
            $d = new \DB\Table\PassHead();
            $d->delete();
        }
        while ($id =  $R->getDataTable(\DB\Table\PassHead::getName())){
            mPrint::R($id,mPrint::PINK);
        }

        if ($argv[1] == 'Clear') {
            $d = new \DB\Table\PassTable();
            $d->delete();
        }
        while ($id =  $R->getDataTable(\DB\Table\PassTable::getName())){
            mPrint::R($id,mPrint::PINK);
        }


        if ($argv[1] == 'Clear') {
            $d = new \DB\Table\PassStatus();
            $d->delete();
        }
        while ($id =  $R->getDataTable(\DB\Table\PassStatus::getName())){
            mPrint::R($id,mPrint::PINK);
        }


        if ($argv[1] == 'Clear') {
            $d = new \DB\Table\PassFields();
            $d->delete();
        }
        while ($id =  $R->getDataTable(\DB\Table\PassFields::getName())){
            mPrint::R($id,mPrint::PINK);
        }


        if ($argv[1] == 'Clear') {
            $d = new \DB\Table\Car();
            $d->delete();
        }
        while ($id =  $R->getDataTable(\DB\Table\Car::getName())){
            mPrint::R($id,mPrint::PINK);
        }
    }
}

