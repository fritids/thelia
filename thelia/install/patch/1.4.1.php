<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Baseobj.class.php");

	/* ------------------------------------------------------------------ */
		
	$version = new Variable();
	$version->charger("version");
	$version->valeur = "140";
	$version->maj();
		
?>