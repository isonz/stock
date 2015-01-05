<?php
$days = isset($_REQUEST['days']) ? $_REQUEST['days'] : date('Y-m-d');
$ticker = isset($_REQUEST['ticker']) ? $_REQUEST['ticker'] : null;

$mains = array();
$cates = array();
if($ticker && $days){
	$mains = MFlow::getMainRetailFlows($days, $ticker);
	$cates = MFlow::getCategoryFlows($days, $ticker);
}

Templates::Assign('days', $days);
Templates::Assign('ticker', $ticker);
Templates::Assign('cates', $cates);
Templates::Assign('mains', $mains);
Templates::Display('mflow.tpl');