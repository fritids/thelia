<?php
	include_once("../classes/Cnx.class.php");
	include_once("../classes/Variable.class.php");
	include_once("../classes/Adresse.class.php");
	include_once("../classes/Venteadr.class.php");
	include_once("../classes/Commande.class.php");
	include_once("../classes/Client.class.php");
	
	$cnx = new Cnx();
	
	$query_cnx = "CREATE TABLE `racmodule` (
	`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	`module` TEXT NOT NULL
	) ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);	

	$query_cnx = "ALTER TABLE `stock` ADD `surplus` FLOAT NOT NULL ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);	

	$query_cnx = "ALTER TABLE `contenudesc` ADD `postscriptum` TEXT NOT NULL AFTER `description` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);	

	$query_cnx = "ALTER TABLE `dossierdesc` ADD `postscriptum` TEXT NOT NULL AFTER `description` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "ALTER TABLE `rubriquedesc` ADD `postscriptum` TEXT NOT NULL AFTER `description` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "ALTER TABLE `produitdesc` ADD `postscriptum` TEXT NOT NULL AFTER `description` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "ALTER TABLE `client` ADD `intracom` TEXT NOT NULL AFTER `siret` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "update pays set tva=0;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "update pays set tva=1 where id in (5,13,20,31,40,51,58,59,63,64,118,69,78,83,86,97,102,103,110,137,140,141,145,146,147,162,163,167);";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "ALTER TABLE `commande` ADD `lang` INT NOT NULL ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "CREATE TABLE `venteadr` (
	  `id` int(11) NOT NULL auto_increment,
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
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$query_cnx = "ALTER TABLE `commande` CHANGE `adresse` `adrlivr` INT( 11 ) NOT NULL DEFAULT '0'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "ALTER TABLE `commande` ADD `adrfact` INT NOT NULL AFTER `client` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		
	$commande = new Commande();
	$query_cmd = "select * from $commande->table";
	$resul_cmd = mysql_query($query_cmd, $commande->link);
	while($row_cmd = mysql_fetch_object($resul_cmd)){
		$tmpcmd = new Commande();
		$tmpcmd->charger($row_cmd->id);
		$client = new Client();
		$client->charger_id($row_cmd->client);
		
		$adr = new Venteadr();
		$adr->raison = $client->raison;
		$adr->nom = $client->nom;
		$adr->prenom = $client->prenom;
		$adr->adresse1 = $client->adresse1;
		$adr->adresse2 = $client->adresse2;
		$adr->adresse3 = $client->adresse3;
		$adr->cpostal = $client->cpostal;		
		$adr->ville = $client->ville;		
		$adr->tel = $client->telfixe . " / " . $client->telport;		
		$adr->pays = $client->pays;
		$adrcli = $adr->add();
		$tmpcmd->adrfact = $adrcli;	
		
		$adr = new Venteadr();
		
		if($commande->adrlivr){
			
			$livraison = new Adresse();
			$livraison->charger($commande->adrlivr);
			
			$adr->raison = $livraison->raison;
			$adr->nom = $livraison->nom;
			$adr->prenom = $livraison->prenom;
			$adr->adresse1 = $livraison->adresse1;
			$adr->adresse2 = $livraison->adresse2;
			$adr->adresse3 = $livraison->adresse3;
			$adr->cpostal = $livraison->cpostal;		
			$adr->ville = $livraison->ville;		
			$adr->tel = $livraison->tel;		
			$adr->pays = $livraison->pays;		
			
		}
		
		else{
			$adr->raison = $client->raison;
			$adr->nom = $client->nom;
			$adr->prenom = $client->prenom;
			$adr->adresse1 = $client->adresse1;
			$adr->adresse2 = $client->adresse2;
			$adr->adresse3 = $client->adresse3;
			$adr->cpostal = $client->cpostal;		
			$adr->ville = $client->ville;		
			$adr->tel = $client->telfixe . " / " . $client->telport;		
			$adr->pays = $client->pays;
		}
		
		$adrlivr = $adr->add();
		$tmpcmd->adrlivr = $adrlivr;		
		
		$tmpcmd->maj();	
	}
			
	$version = new Variable();
	$version->charger("version");
	$version->valeur = "137";
	$version->maj();
	
?>