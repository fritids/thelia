<?php
	include_once("../classes/Cnx.class.php");
	include_once("../classes/Variable.class.php");
	
	$cnx = new Cnx();
	
	$query_cnx = "CREATE TABLE `racmodule` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`module` TEXT NOT NULL
	) ;";
	$resul_cnx = mysql_query($query_cnx, $var->link);	

	$query_cnx = "ALTER TABLE `stock` ADD `surplus` FLOAT NOT NULL ;";
	$resul_cnx = mysql_query($query_cnx, $var->link);	

	$version = new Variable();
	$version->charger("version");
	$version->valeur = "137";
	$version->maj();
	
?>