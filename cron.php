<?php
require_once('config.php');
foreach (glob(_LIBS."/*.php") as $libs){
	require_once $libs;
}
foreach (glob(_MODS."/*.php") as $mods){
	require_once $mods;
}





