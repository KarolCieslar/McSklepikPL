<?php

	session_start();
	$_SESSION['logged']==false;
	$_SESSION['user']==false;
	header('Location: index.php');
	session_unset();

?>