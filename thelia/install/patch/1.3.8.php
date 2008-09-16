<?php
	include_once("../classes/Cnx.class.php");
	include_once("../classes/Variable.class.php");

	$version = new Variable();
	$version->charger("version");
	$version->valeur = "137";
	$version->maj();
	
?>