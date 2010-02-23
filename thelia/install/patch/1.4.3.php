<?php
	include_once(realpath(dirname(__FILE__)) . "/../../config/Cnx.class.php");

	$cnx = new Cnx();

	$query_cnx = "ALTER TABLE `administrateur` ADD  `lang` INT NOT NULL ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "update administrateur set lang=1";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "ALTER TABLE  `statut` ADD  `nom` TEXT NOT NULL ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "update statut set nom=\"nonpaye\" where id=1";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	$query_cnx = "update statut set nom=\"paye\" where id=2";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	$query_cnx = "update statut set nom=\"traitement\" where id=3";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	$query_cnx = "update statut set nom=\"envoye\" where id=4";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	$query_cnx = "update statut set nom=\"annule\" where id=5";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);				


	$query_cnx = "ALTER TABLE  `adresse` ADD  `entreprise` TEXT NOT NULL AFTER  `raison` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	$query_cnx = "ALTER TABLE  `venteadr` ADD  `entreprise` TEXT NOT NULL AFTER  `raison` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	
	$query_cnx = "select * from commande";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	while($row_cnx = mysql_fetch_object($resul_cnx)){
	        $query2_cnx = "select * from client where id=" . $row_cnx->client;
	        $resul2_cnx = mysql_query($query2_cnx, $cnx->link);
	        $row2_cnx = mysql_fetch_object($resul2_cnx);

	        $query3_cnx = "update venteadr set entreprise=\""  . $row2_cnx->entreprise ."\" where id=" . $row_cnx->adrfact;
	        $resul3_cnx = mysql_query($query3_cnx, $cnx->link);
	}

	$query_cnx = "update variable set valeur='143' where nom='version'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		
?>
