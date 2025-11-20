<?php

use DB\Proc\Proc_TriggerNumberCamera;
use views\mPrint;

session_start();
require "Properties/error_reporting.php";
require "spl_autoload.php";

$R = new models\RefreshDataFormOrion();

$R->updateSPR(\DB\Table\GrAccess::getName());
$R->updateSPR(\DB\Table\AcessPoint::getName());

$R = new models\RefreshDataFormPS();
if ($R->testConnection()){
    $R->getSession();
    if ($R->authorizationOnThePS()){
        $R->getCarPrivilege();
    }
}
