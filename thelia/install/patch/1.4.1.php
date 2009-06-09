<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Baseobj.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");

	/* ------------------------------------------------------------------ */
		
	$query_cnx = "update variable set version='141' where nom='version'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

		
?>