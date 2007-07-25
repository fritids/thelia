<?php

	include_once("../../../classes/Commande.class.php");
	include_once("../../../fonctions/divers.php");
		
	$ref = $_POST['ref'];
	$erreur = $_POST['erreur'];
	$auto = $_POST['auto'];
		
	$commande = new Commande();

	$commande->charger_trans($ref);
	if($erreur=="00000" && $auto!="XXXXXX"){
	 $commande->statut = 2;
	 $commande->genfact();
	}
	
	
	$commande->maj();

	modules_fonction("confirmation", $commande);

?>