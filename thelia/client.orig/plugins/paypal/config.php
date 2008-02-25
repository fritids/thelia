<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");

	// Modifier la valeur ci-dessous avec l'e-mail de vote compte PayPal
	$compte_paypal = 'mail@domaine.com';

	$Devise        = "EUR";
	$Code_Langue   = "FR";

	$urlsite = new Variable();
	$urlsite->charger("urlsite");
	
	$serveur="https://www.paypal.com/cgi-bin/webscr";
    $confirm = $urlsite->valeur."/client/plugins/paypal/confirmation.php";
	$retourok = $urlsite->valeur."/merci.php";
	$retournok = $urlsite->valeur."/regret.php";
	
	
	
	
?>
