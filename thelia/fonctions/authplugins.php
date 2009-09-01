<?php
	include_once(realpath(dirname(__FILE__)) . "/../classes/Administrateur.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../classes/Navigation.class.php");
	
	if(! session_id())
		session_start();
	if( ! isset($_SESSION["util"]->id) ) exit;
?>