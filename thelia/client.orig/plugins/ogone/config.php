<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");


	// Modifier la valeur ci-dessous avec l'id de votre boutique fourni par Ogone (identique à votre login d'accès client)
	$pspid = 'VOTRE_PSPID';

	$devise        = "EUR";
	$langue   = "fr_FR";

	$urlsite = new Variable();
	$urlsite->charger("urlsite");

	$nomsite = new Variable();
	$nomsite->charger("nomsite");
		
	$serveur="https://secure.ogone.com/ncol/prod/orderstandard.asp";
    $confirm = $urlsite->valeur."/client/plugins/ogone/confirmation.php";
	$retourok = $urlsite->valeur."/merci.php";
	$retourko = $urlsite->valeur."/regret.php";
	

?>