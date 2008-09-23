<?php
	include_once("../classes/Cnx.class.php");
	include_once("../classes/Rubrique.class.php");
	include_once("../classes/Produit.class.php");
	include_once("../classes/Variable.class.php");
	include_once("../classes/Message.class.php");
	include_once("../classes/Messagedesc.class.php");

	$rub = new Rubrique();
	$query_cnx = "select * from $rub->table";
	$resul_cnx = mysql_query($query_cnx, $rub->link);

	$prod = new Produit();
	
	while($row_cnx = mysql_fetch_object($resul_cnx)){
		$query_prod = "select * from $prod->table where rubrique=\"" . $row_cnx->id . "\" order by classement";
		$resul_prod = mysql_query($query_prod, $prod->link);
		$i = 0;
		while($row_prod = mysql_fetch_object($resul_prod)){
			$tmpprod = new Produit();
			$tmpprod->charger($row_prod->ref);
			$tmpprod->classement = ++$i;
			$tmpprod->maj();
			
		}
	}

	$messagedesc = new Messagedesc();

	$message = new Message();
	$message->charger("mdpmodif");	
	$message->supprimer();
	
	$message = new Message();
	$message->charger("mdpnonvalide");
	$message->supprimer();
	
	$message = new Message();
	$message->charger("corpscommande1");
	$message->nom = "mailconfirmcli";
	$message->maj();
	
	$message = new Message();
	$message->charger("corpscommande2");
	$message->nom = "mailconfirmadm";
	$message->maj();
		
	$message = new Message();
	$message->charger("nouveaumdp2");
	$message->nom = "changepass";
	$message->maj();
		
	$cnx = new Cnx();
	$query_cnx = "ALTER TABLE `messagedesc` ADD `intitule` TEXT NOT NULL AFTER `lang` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);	

	$message = new Message();
	$message->charger("mailconfirmcli");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$messagedesc->intitule = "Mail de confirmation client";
	$messagedesc->maj();

	$message = new Message();
	$message->charger("mailconfirmadm");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$messagedesc->intitule = "Mail de confirmation administrateur";
	$messagedesc->maj();

	$message = new Message();
	$message->charger("changepass");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$messagedesc->intitule = "Mail de changement de mot de passe";
	$messagedesc->maj();
	
	$message = new Message();
	$message->charger("colissimo");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$messagedesc->intitule = "Mail de confirmation d'envoi colissimo";
	$messagedesc->maj();

	$message = new Message();
	$message->charger("nouveaumdp1");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$titre = $messagedesc->description;
	$message->supprimer();

	$message = new Message();
	$message->charger("changepass");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$messagedesc->titre = $titre;
	$messagedesc->maj();	

	$message = new Message();
	$message->charger("sujetcommande");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$titre = $messagedesc->description;
	$message->supprimer();

	$message = new Message();
	$message->charger("mailconfirmcli");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$messagedesc->titre = $titre . "__COMMANDE_REF__";
	$messagedesc->maj();	

	$cnx = new Cnx();
	$query_cnx = "update commande set lang='1'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		
	$version = new Variable();
	$version->charger("version");
	$version->valeur = "138";
	$version->maj();
	
?>