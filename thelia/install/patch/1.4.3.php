<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");

	$query_cnx = "ALTER TABLE `administrateur` ADD  `lang` INT NOT NULL ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "update administrateur set lang=1";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "update variable set valeur='143' where nom='version'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

		
?>