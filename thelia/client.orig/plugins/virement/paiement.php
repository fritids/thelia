<?php
	include_once("../../../classes/Variable.class.php");
	$urlsite = new Variable();
	$urlsite->charger("urlsite");
		
	header("Location: " . $urlsite->valeur . "/virement.php")
?>