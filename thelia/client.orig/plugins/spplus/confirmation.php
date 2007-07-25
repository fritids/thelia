<?php

	include_once("../../../classes/Commande.class.php");	
	include_once("../../../fonctions/divers.php");	
	
	$reference = $_POST['reference'];
	$etat = $_POST['etat'];
	
	$commande = new Commande();

	$commande->charger_trans($reference);
	if($etat == "1"){
	 $commande->statut = 2;
	 $commande->genfact();
	}
	
	$commande->maj();

	modules_fonction("confirmation", $commande);
	
?>
