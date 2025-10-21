<?php
require "Properties/error_reporting.php";

/**
 * Является единственной точкой входа для всех инструкциф приложения
 */

set_time_limit(0);


if (array_key_exists('sessionHandle',$_REQUEST)){
    session_id($_REQUEST['sessionHandle']);
}



session_start();

/**
 * spl_autoload_register.php Содержит
 * функцию spl_autoload_register которая автоматически подгружает клас по его nameSpace
 */
require "spl_autoload.php";

/**
 * Создаётся и исполняется основной контроллер (маршрутизатор) всего приложения
 */
$router = \models\Router::get();
$router->AppRun();
