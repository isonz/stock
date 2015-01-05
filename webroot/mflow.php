<?php
$days = isset($_REQUEST['days']) ? $_REQUEST['days'] : date('Y-m-d');
$ticker = isset($_REQUEST['ticker']) ? $_REQUEST['ticker'] : null;

$data = array();
$mains = array();
$cates = array();
if($ticker && $days){
	$data = MFlow::getData($days, $ticker);
	$mains = MFlow::getMainRetailFlows($days, $ticker);
	$cates = MFlow::getCategoryFlows($days, $ticker);
}

Templates::Assign('cvs', MFlow::$cvs);
Templates::Assign('total', MFlow::$total);
Templates::Assign('days', $days);
Templates::Assign('ticker', $ticker);
Templates::Assign('data', $data);
Templates::Assign('cates', $cates);
Templates::Assign('mains', $mains);
Templates::Display('mflow.tpl');