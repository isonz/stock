<?php
set_time_limit(0);
ini_set('memory_limit', -1);

require_once('config.php');
foreach (glob(_LIBS."/*.php") as $libs){
	require_once $libs;
}
foreach (glob(_MODS."/*.php") as $mods){
	require_once $mods;
}

Sina::dataRun();
Sina::holderRun();



