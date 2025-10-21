<?php
error_reporting(~E_NOTICE & ~E_DEPRECATED & ~E_STRICT );
require "spl_autoload.php";

$object = new \DB\RefreshObjectDB(\DB\RefreshObjectDB::View);
$object->refresh();
