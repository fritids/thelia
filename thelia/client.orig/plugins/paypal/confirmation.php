<?php


include_once("../../../classes/Commande.class.php");	

if($_POST['STATUS'] == "9"){

	$commande = new Commande();
	$commande->charger_trans($_POST['orderID']);
    $commande->statut = 2;
    $commande->genfact();
	$commande->maj();

	modules_fonction("confirmation", $commande);

}

?>
