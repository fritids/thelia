<?php
	include_once("../../classes/Produit.class.php");
	include_once("../../classes/Produitdesc.class.php");

	$sep = explode( "_", $_POST['id']);

	$pos = strpos($_POST['id'], "_");
	
	$modif = substr($_POST['id'], 0, $pos);
	
	$prod = new Produit();
	$prod->charger(substr($_POST['id'], $pos+1));

	$proddesc = new Produitdesc();
	$proddesc->charger($prod->id);
	
	switch($modif){
		case 'prix' : $prod->prix = $_POST['value']; echo $prod->prix; break;
		case 'prix2' :  $prod->prix2 = $_POST['value']; echo $prod->prix2; break;
		case 'stock' :  $prod->stock = $_POST['value']; echo $prod->stock; break;
		case 'titre' :  $proddesc->titre = utf8_decode($_POST['value']); echo $proddesc->titre; break;

	}

	$prod->maj();
	$proddesc->maj();
	
?>