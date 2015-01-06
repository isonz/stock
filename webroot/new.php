<?php
$num = isset($_GET['n']) ? $_GET['n'] : 10;

$data = Stock::getNewStock($num);

Templates::Assign('n', $num);
Templates::Assign('data', $data);
Templates::Display('new.tpl');
