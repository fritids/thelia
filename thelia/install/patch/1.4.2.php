<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");

	/* ------------------------------------------------------------------ */



	$cnx = new Cnx();
	
	$query_cnx = "CREATE TABLE `autorisation` ( 
	  `id` int(11) NOT NULL auto_increment,
	  `nom` text NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `autorisationdesc` (
	  `id` int(11) NOT NULL auto_increment,
	  `autorisation` int(11) NOT NULL,
	  `titre` text NOT NULL,
	  `chapo` text NOT NULL,
	  `description` text NOT NULL,
	  `postscriptum` text NOT NULL,
	  `lang` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `autorisation_administrateur` (
	  `id` int(11) NOT NULL auto_increment,
	  `administrateur` int(11) NOT NULL,
	  `autorisation` int(11) NOT NULL,
	  `lecture` smallint(6) NOT NULL,
	  `ecriture` smallint(6) NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `autorisation_profil` (
	  `id` int(11) NOT NULL auto_increment,
	  `profil` int(11) NOT NULL,
	  `autorisation` int(11) NOT NULL,
	  `lecture` int(11) NOT NULL,
	  `ecriture` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `profil` (
	  `id` int(11) NOT NULL auto_increment,
	  `nom` text NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);

	$query_cnx = "CREATE TABLE `profildesc` (
	  `id` int(11) NOT NULL auto_increment,
	  `profil` int(11) NOT NULL,
	  `titre` text NOT NULL,
	  `chapo` text NOT NULL,
	  `description` text NOT NULL,
	  `postscriptum` text NOT NULL,
	  `lang` int(11) NOT NULL,
	  PRIMARY KEY  (`id`)
	) AUTO_INCREMENT=1 ;";
	$resul_cnx = mysql_query($query_cnx,$cnx->link);
	

	$query_cnx = "update variable set valeur='142' where nom='version'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

		
?>