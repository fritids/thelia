<?php

	include_once("../../../classes/Commande.class.php");
	include_once("../../../fonctions/divers.php");

	include_once('config.php');
	include_once('lib/lib_debug.php');
	include_once('lib/paylineSDK.php');

	$array = array();
	$payline = new paylineSDK();
	$response = $payline->get_webPaymentDetails($_REQUEST['token']);
	
	if(isset($response) && $response['result']['code'] == "0000"){
		$commande = new Commande();
		$commande->charger_trans($_REQUEST['token']);
    	$commande->statut = 2;
    	$commande->genfact();
		$commande->maj();
}
?>
