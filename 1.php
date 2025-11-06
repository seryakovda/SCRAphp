<?php

use DB\Proc\Proc_TriggerNumberCamera;

session_start();
require "Properties/error_reporting.php";
require "spl_autoload.php";


$R = new models\RefreshDataFormOrion();
$R->getFull_pList_start();
//$R->getFull_pMark_next();