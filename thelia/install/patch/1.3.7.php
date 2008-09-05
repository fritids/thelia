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

	$query_cnx = "ALTER TABLE `contenudesc` ADD `postscriptum` TEXT NOT NULL AFTER `description` ;";
	$resul_cnx = mysql_query($query_cnx, $var->link);	

	$query_cnx = "ALTER TABLE `dossierdesc` ADD `postscriptum` TEXT NOT NULL AFTER `description` ;";
	$resul_cnx = mysql_query($query_cnx, $var->link);
	
	$query_cnx = "ALTER TABLE `rubriquedesc` ADD `postscriptum` TEXT NOT NULL AFTER `description` ;";
	$resul_cnx = mysql_query($query_cnx, $var->link);
	
	$query_cnx = "ALTER TABLE `produitdesc` ADD `postscriptum` TEXT NOT NULL AFTER `description` ;";
	$resul_cnx = mysql_query($query_cnx, $var->link);

	$query_cnx = "ALTER TABLE `client` ADD `intracom` TEXT NOT NULL AFTER `siret` ;";
	$resul_cnx = mysql_query($query_cnx, $var->link);

	$query_cnx = "update pays set tva=0;";
	$resul_cnx = mysql_query($query_cnx, $var->link);
	
	$query_cnx = "update pays set tva=1 where id in (5,13,20,31,40,51,58,59,63,64,118,69,78,83,86,97,102,103,110,137,140,141,145,146,147,162,163,167);";
	$resul_cnx = mysql_query($query_cnx, $var->link);


	$version = new Variable();
	$version->charger("version");
	$version->valeur = "137";
	$version->maj();
	
?>