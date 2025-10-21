<?php
require "Properties/error_reporting.php";

session_start();
require_once __DIR__.'/frontPage/index.html';
require "index_ajax.php";


/**
 * Данный файл исполняется только в случае полной презагрузке страницы.
 *
 * Он подключает базовый HTML файл, который передаёт браузеру только основные теги <head> и <body>
 * с некоторыми <script> и CSS
 * остальная работа проводится с использованием AJAX через файл index_ajax.php.
 *
 * index_ajax.php является единственной точкой входа для всех инструкциф приложения
 */
