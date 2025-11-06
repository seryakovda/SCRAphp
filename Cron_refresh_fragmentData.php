<?php

use DB\Proc\Proc_TriggerNumberCamera;
use views\mPrint;

session_start();
require "Properties/error_reporting.php";
require "spl_autoload.php";

$R = new models\RefreshDataFormOrion();
$R->refresh_pList_afterLastId();
$R->refresh_pMark_afterLastId();
