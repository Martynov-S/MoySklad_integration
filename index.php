<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
//ini_set('display_errors', 'off');
define('ROOTDIR', __dir__);
include_once 'functions.php';
spl_autoload_register('classAutoLoad');
Config::init();
Router::route($_SERVER['REQUEST_URI']);
$template = 'basic.tpl';
ob_start();
include_once 'module/mod_'.Router::$module.'.php';
$content = ob_get_clean();
ob_start();
include_once "template/$template";
ob_end_flush();
?>