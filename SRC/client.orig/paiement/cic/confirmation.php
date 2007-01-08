<?php

	include_once("../../../classes/Commande.class.php");	


    $reference = $_POST['reference'];
    $etat = $_POST['code-retour'];
        
	$commande = new Commande();
	$commande->charger_trans($reference);
	if($etat == "payetest" || $etat=="paye"){
	 $commande->statut = 2;
	 $commande->genfact();
	}

	$commande->maj();
	
	$commande->destroy();
	
?>
