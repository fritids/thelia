<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");


	// Modifiez la valeur ci-dessous avec l'id de votre boutique fourni par Bluepaid (identique à votre login d'accès client)
	$id_boutique = 'ID_DE_LA_BOUTIQUE';

	$Devise        = "EUR";
	$Code_Langue   = "FR";

	$urlsite = new Variable();
	$urlsite->charger("urlsite");
	
	$serveur="https://www.bluepaid.com/in.php";
    $confirm = $urlsite->valeur."/client/plugins/bluepaid/confirmation.php";
	$retourok = $urlsite->valeur."/merci.php";
	$retourko = $urlsite->valeur."/regret.php";
	

?>