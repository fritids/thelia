<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Baseobj.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");

	/* ------------------------------------------------------------------ */
	
	$cnx = new Cnx();

	$query_cnx = "ALTER TABLE  `commande` CHANGE  `facture`  `facture` INT NOT NULL";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		
	$query_cnx = "update variable set valeur='141' where nom='version'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

		
?>