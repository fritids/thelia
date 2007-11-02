<?php


include_once("../../../classes/Commande.class.php");	

if($_POST['etat'] == "ok"){

	$commande = new Commande();
	$commande->charger_trans($_POST['id_client']);
    $commande->statut = 2;
    $commande->genfact();
	$commande->maj();

	modules_fonction("confirmation", $commande);

}

?>
