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

	$query_cnx = "ALTER TABLE `commande` ADD `lang` INT NOT NULL ;";
	$resul_cnx = mysql_query($query_cnx, $var->link);

	$query_cnx = "CREATE TABLE `venteadr` (
	  `id` int(11) NOT NULL auto_increment,
	  `commande` int(11) NOT NULL,
	  `type` smallint(6) NOT NULL,
	  `raison` smallint(6) NOT NULL default '0',
	  `nom` text NOT NULL,
	  `prenom` text NOT NULL,
	  `adresse1` varchar(40) NOT NULL default '',
	  `adresse2` varchar(40) NOT NULL default '',
	  `adresse3` varchar(40) NOT NULL default '',
	  `cpostal` varchar(10) NOT NULL default '',
	  `ville` varchar(30) NOT NULL default '',
	  `tel` text NOT NULL,
	  `pays` int(11) NOT NULL default '0',
	  PRIMARY KEY  (`id`)
	)AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx, $var->link);

	$version = new Variable();
	$version->charger("version");
	$version->valeur = "137";
	$version->maj();
	
?>