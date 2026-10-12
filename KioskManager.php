<?php
use models\ErrorLog;
use mysql_xdevapi\Exception;
use views\mPrint;

session_start();
require "Properties/error_reporting.php";
require "spl_autoload.php";
header("content-type:application/json");
print '{"reboot":false}';
