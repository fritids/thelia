<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");

	$titre1="Paypal";
	$chapo1="Carte bancaire";
	$description1="";
	
	$titre2="";
	$chapo2="";
	$description2="";
	
	$titre3="";
	$chapo3="";
	$description3="";

	// Modifier la valeur ci-dessous avec l'e-mail de vote compte PayPal
	$compte_paypal = 'VOTRE E-MAIL PAYPAL';

	$Devise        = "EUR";
	$Code_Langue   = "FR";



	$urlsite = new Variable();
	$urlsite->charger("urlsite");
	
	$serveur="https://www.paypal.com/cgi-bin/webscr";
    $confirm = $urlsite->valeur."/client/plugins/paypal/confirmation.php";
	$retourok = $urlsite->valeur."/merci.php";
	$retournok = $urlsite->valeur."/regret.php";
	

?>