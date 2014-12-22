<?php
error_reporting(E_ALL);
ini_set("display_startup_errors","1");
ini_set("display_errors","On");

//======================================= Basic
if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if(!defined('_SITE')){
    define('_SITE', dirname(__FILE__) . DS);
}
if(!defined('_LIBS')){
    define('_LIBS', _SITE . 'libs' . DS);
}
if(!defined('_MODS')){
	define('_MODS', _SITE . 'mods' . DS);
}
if(!defined('_VIEWS')){
	define('_VIEWS', _SITE . 'views' . DS);
}
if(!defined('_DATA')){
	define('_DATA', _SITE . 'data' . DS);
}
if(!defined('_LOGS')){
    define('_LOGS', _DATA . 'logs' . DS);
}
//------------------------ encoding
if(!defined('_ENCODING')){
	define('_ENCODING', 'UTF-8');
}
//======================================= Smarty
if(!defined('_SMARTY')){
	define('_SMARTY', _LIBS . 'Smarty' . DS);
}
if(!defined('_SMARTY_TEMPLATE')){
	define('_SMARTY_TEMPLATE', _SITE .'template' . DS);
}
if(!defined('_SMARTY_COMPILED')){
	define('_SMARTY_COMPILED', _DATA . 'compileds' . DS);
}
if(!defined('_SMARTY_CACHE')){
	define('_SMARTY_CACHE', _DATA . 'caches' . DS);
}
//======================================== Config
$GLOBALS['CONFIG_DATABASE'] = array(
	'host'      => '127.0.0.1',
    'user'      => 'root',
    'pwd'       => 'admin888',
    'dbname'    => 'stock',
	'port'      => 3306,
	'tb_prefix' => ''
);

require_once 'setting.php';



