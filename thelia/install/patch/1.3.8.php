<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Rubrique.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Produit.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Variable.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Message.class.php");
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Messagedesc.class.php");

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
	$cnx = new Cnx();
	
	$query_cnx = "DELETE from $message->table where nom='mdpmodif'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "DELETE from $message->table where nom='mdpnonvalide'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "UPDATE $message->table set nom='mailconfirmadm' where nom='corpscommande2'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "UPDATE $message->table set nom='changepass' where nom='nouveaumdp2'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "UPDATE $message->table set nom='mailconfirmcli' where nom='corpscommande1'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "ALTER TABLE `messagedesc` ADD `intitule` TEXT NOT NULL AFTER `lang` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);	

	$message = new Message();
	$message->charger("mailconfirmcli");
	$query_cnx = "UPDATE $messagedesc->table set intitule='Mail de confirmation client' where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	

	$message = new Message();
	$message->charger("mailconfirmadm");
	$query_cnx = "UPDATE $messagedesc->table set intitule='Mail de confirmation administrateur' where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	

	$message = new Message();
	$message->charger("changepass");
	$query_cnx = "UPDATE $messagedesc->table set intitule='Mail de changement de mot de passe' where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$message = new Message();
	$message->charger("colissimo");
	$query_cnx = "UPDATE $messagedesc->table set intitule=\"Mail de confirmation d'envoi colissimo\" where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$message = new Message();
	$message->charger("nouveaumdp1");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$titre = $messagedesc->description;
	$message->supprimer();
	

	$message = new Message();
	$message->charger("changepass");
	$query_cnx = "UPDATE $messagedesc->table set titre='$titre' where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		

	$message = new Message();
	$message->charger("sujetcommande");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$titre = $messagedesc->description;
	$message->supprimer();

	$message = new Message();
	$message->charger("mailconfirmcli");
	$query_cnx = "UPDATE $messagedesc->table set titre=CONCAT(titre,\"$titre\") where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);	


	$query_cnx = "update commande set lang='1'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		
	$query_cnx = "update variable set version='138' where nom='version'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
?>