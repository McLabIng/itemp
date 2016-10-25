<?php 
session_start();
$_SESSION = array();
session_destroy();
	echo "<script type=\"text/javascript\">window.location=\"index.php?mod=autenticacion\";</script>";
?>