<?php
require_once('../config.php');
foreach (glob(_LIBS."/*.php") as $libs){
	require_once $libs;
}
foreach (glob(_MODS."/*.php") as $mods){
	require_once $mods;
}
foreach (glob(_SMARTY."/*.php") as $lib_smarty){
	require_once $lib_smarty;
}

//----------------- user
session_start();
$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
Templates::Assign('user', $user);
if(!$user){
	require_once 'sign.php';
	exit;
}

//---------------- 控制器
$uri = isset($_SERVER["REQUEST_URI"]) ? $_SERVER["REQUEST_URI"] : null;
if($uri){
	$uri = explode("/", $uri);
	$action = isset($uri[1]) ? $uri[1] : null;
	$action = explode("?", $action); 
	$action = isset($action[0]) ? $action[0] : null;
}
if($action){
	$action = $action.".php";
	$flag = 0;
	foreach (glob("*.php") as $webroot){
		if($action === $webroot){
			require_once $action;
			$flag = 1;
			exit;
		}
	}
	if(!$flag){
		header("Location: html/404.html");
		exit;
	}
}
include_once 'home.php';



