<?php
$from_date = isset($_GET['from']) ? $_GET['from'] : strtotime(date('Y-m-d'));
$day_num = isset($_GET['num']) ? $_GET['num'] : 10;
$data = Datas::getOptimizationData($from_date, $day_num);

Templates::Assign('data', $data);
Templates::Display('home.tpl');