<?php

	include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");


	$mode = '1';
	$site = 'SITE';
	$rang = 'RANG';
	$id = 'IDENTIFIANT';
	$lang = 'FR';
	$devise = '978';
	
	$urlsite = new Variable();
	$urlsite->charger("urlsite");
	
	$serveur="http://www.site.com/cgi-bin/modulev2.cgi";
    $confirm = $urlsite->valeur."/client/plugins/paybox/confirmation.php";
	$retourok = $urlsite->valeur."/merci.php";
	$retourko = $urlsite->valeur."/regret.php";
	

?>