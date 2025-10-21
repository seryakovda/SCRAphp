<?php
error_reporting(~E_NOTICE & ~E_DEPRECATED & ~E_STRICT );
require "spl_autoload.php";

$object = new \DB\RefreshTables();
// начало тестирования
$object->createNewTale();
$object->matchingTable();
$object->matchingIndexes_For_DROP_CREATE();
$object->matchingIndexes_For_RECREATE();
$object->reCreateTriggers();
require_once "RefreshTableDefRows.php";