<?php
$to = isset($_REQUEST['to']) ? $_REQUEST['to'] : date('Y-m-d');
$from = isset($_REQUEST['from']) ? $_REQUEST['from'] : date('Y-m-d', (strtotime($to) - (3600*24*10)));

$tto = strtotime($to);
$ffrom = strtotime($from);

$data = Susp::getList("days >= $ffrom AND days <= $tto");

Templates::Assign('to', $to);
Templates::Assign('from', $from);
Templates::Assign('data', $data);
Templates::Display('susp.tpl');
