<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");

	/* ------------------------------------------------------------------ */
	
	$cnx = new Cnx();
		
	$query_cnx = "update variable set valeur='142' where nom='version'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

		
?>