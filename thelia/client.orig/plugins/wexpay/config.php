<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");

	$titre1="weXpay";
	$chapo1="E-money";
	$description1="";
	
	$titre2="";
	$chapo2="";
	$description2="";
	
	$titre3="";
	$chapo3="";
	$description3="";

	// Modifier la valeur ci-dessous avec votre login
	$id_marchand = ''; // Format MD5
	$login = '';
	$pass = '';
	$urltransaction = 'partenaires.wexpay.com/marchands/2/transactions.xml';
	
	$urlsite = new Variable();
	$urlsite->charger("urlsite");
	
	$serveur="https://paiements.wexpay.fr";
?>
