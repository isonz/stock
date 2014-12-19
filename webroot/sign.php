<?php
if(isset($_GET['in'])){
	$user = isset($_POST['user']) ? $_POST['user'] : null;
	$passwd = isset($_POST['passwd']) ? $_POST['passwd'] : null;
	if($user && $passwd){
		if('ison' === $user && '4889c9752829060bc7b19d0fb55b54b0' === md5(md5($passwd))){
			$_SESSION['user'] = $user;
			header('Location: /');
		}
	}
}

if(isset($_GET['out'])){
	session_destroy();
	session_unset();
	unset($_SESSION);
}

$user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
Templates::Assign('user', $user);
Templates::Display('sign.tpl');

