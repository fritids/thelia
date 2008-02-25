<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");


	// Modifier la valeur ci-dessous avec l'id de votre boutique fourni par Ogone (identique à votre login d'accès client)
	$pspid = 'VOTRE_PSPID';


	$urlsite = new Variable();
	$urlsite->charger("urlsite");
	
	$serveur="https://www.paypal.com/cgi-bin/webscr";
    $confirm = $urlsite->valeur."/client/plugins/paypal/confirmation.php";
	$retourok = $urlsite->valeur."/merci.php";
	$retournok = $urlsite->valeur."/regret.php";
	

?>
