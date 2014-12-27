<?php
$holder = isset($_GET['name']) ? $_GET['name'] : null;

$data = array();
if($holder) $data = Holder::getHolderInfoByName($holder);

Templates::Assign('data', $data);
Templates::Display('holder.tpl');