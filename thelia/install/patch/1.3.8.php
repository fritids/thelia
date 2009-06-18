<?php
	include_once(realpath(dirname(__FILE__)) . "/../../classes/Cnx.class.php");

	$cnx = new Cnx();
	
	$query_cnx = "select * from rubrique";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	while($row_cnx = mysql_fetch_object($resul_cnx)){
		$query_prod = "select * from produit where rubrique=\"" . $row_cnx->id . "\" order by classement";
		$resul_prod = mysql_query($query_prod, $cnx->link);
		$i = 0;
		while($row_prod = mysql_fetch_object($resul_prod)){
			++$i;
			$query_prod2 = "update produit set classement=$i where ref=\"" . $row_prod->ref. "\"";
			$resul_prod2 = mysql_query($query_prod2, $cnx->link);
			
		}
	}

	$cnx = new Cnx();
	
	$query_cnx = "DELETE from message where nom='mdpmodif'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "DELETE from message where nom='mdpnonvalide'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "UPDATE message set nom='mailconfirmadm' where nom='corpscommande2'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "UPDATE message set nom='changepass' where nom='nouveaumdp2'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "UPDATE message set nom='mailconfirmcli' where nom='corpscommande1'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$query_cnx = "ALTER TABLE `messagedesc` ADD `intitule` TEXT NOT NULL AFTER `lang` ;";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);	

	$message = new Message();
	$message->charger("mailconfirmcli");
	$query_cnx = "UPDATE messagedesc set intitule='Mail de confirmation client' where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	

	$message = new Message();
	$message->charger("mailconfirmadm");
	$query_cnx = "UPDATE messagedesc set intitule='Mail de confirmation administrateur' where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	

	$message = new Message();
	$message->charger("changepass");
	$query_cnx = "UPDATE messagedesc set intitule='Mail de changement de mot de passe' where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
	$message = new Message();
	$message->charger("colissimo");
	$query_cnx = "UPDATE messagedesc set intitule=\"Mail de confirmation d'envoi colissimo\" where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);

	$message = new Message();
	$message->charger("nouveaumdp1");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$titre = $messagedesc->description;
	$message->supprimer();
	

	$message = new Message();
	$message->charger("changepass");
	$query_cnx = "UPDATE messagedesc set titre='$titre' where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		

	$message = new Message();
	$message->charger("sujetcommande");
	$messagedesc = new Messagedesc();
	$messagedesc->charger($message->id);
	$titre = $messagedesc->description;
	$message->supprimer();

	$message = new Message();
	$message->charger("mailconfirmcli");
	$query_cnx = "UPDATE messagedesc set titre=CONCAT(titre,\"$titre\") where id=$message->id";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);	


	$query_cnx = "update commande set lang='1'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
		
	$query_cnx = "update variable set valeur='138' where nom='version'";
	$resul_cnx = mysql_query($query_cnx, $cnx->link);
	
?>