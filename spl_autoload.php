<?php
/**
 * Created by PhpStorm.
 * User: rezzalbob
 * Date: 03.09.2020
 * Time: 21:31
 */
use \Properties\Security;

spl_autoload_register(function ($class) {
    require_once "Properties/Security.php";
    $path = explode("\\", $class);
    $_SERVER['DOCUMENT_ROOT'] =  Security::DIR();
    $class = $_SERVER['DOCUMENT_ROOT'];

    foreach ($path as $key => $value) {
        switch ($value) {
            case "PhpOffice":
            case "Zend":
            case "Psr":

            case "Liquetsoft":
            case "SbWereWolf":

            case "jamesiarmes":

                $value = "External\\" . $value;
                break;

            case "Devmakis":
                $value = str_replace("Devmakis","External",$value);
                break;

        }
        $class = $class . "\\" . $value;
    }
    $class = $class . '.php';
    $class = str_replace("\\", "/", $class);
    //print "</br>".chr(10).chr(13);
    //print $class."</br>".chr(10).chr(13);
    if (file_exists($class)) {

        require_once $class;
    }
});

