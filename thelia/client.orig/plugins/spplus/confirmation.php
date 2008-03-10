<?php

	include_once("../../../classes/Commande.class.php");	
	include_once("../../../fonctions/divers.php");	
	
	
	$commande = new Commande();

	$commande->charger_trans($_REQUEST['reference']);
	if($_REQUEST['etat'] == "1"){
	 $commande->statut = 2;
	 $commande->genfact();
	}
	else if($_REQUEST['etat'] == "2") {
	 $commande->statut = 5;
	}
	
	$commande->maj();

	modules_fonction("confirmation", $commande);
	
	echo spcheckok;	
?>
