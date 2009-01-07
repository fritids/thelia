<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Variable.class.php");

	$version = new Variable();
	$version->charger("version");
	$version->valeur = "140";
	$version->maj();
	
?>