<?php
require_once('config.php');
foreach (glob(_LIBS."/*.php") as $libs){
	require_once $libs;
}
foreach (glob(_MODS."/*.php") as $mods){
	require_once $mods;
}

$content = '[{symbol:"sh600104",code:"600104",name:"中华"}]';
$content = json_decode($content, true);
var_dump($content);
//Sina::run();




