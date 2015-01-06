<?php
$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
$from = isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d', (strtotime($to) - (3600*24*10)));
$ticker = isset($_REQUEST['ticker']) ? $_REQUEST['ticker'] : null;


$data = array();
if($ticker) $data = Datas::getOneData($ticker, $from, $to);

Templates::Assign('to', $to);
Templates::Assign('from', $from);
Templates::Assign('ticker', $ticker);
Templates::Assign('data', $data);
Templates::Display('stock.tpl');
