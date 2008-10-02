<?php
	include_once("../../classes/Produit.class.php");

	$sep = explode( "_", $_POST['id']);

	$pos = strpos($_POST['id'], "_");
	
	$modif = substr($_POST['id'], 0, $pos);
	
	$prod = new Produit();
	$prod->charger(substr($_POST['id'], $pos+1));

	$prod->$modif = $_POST['value'];
	
	$prod->maj();

	echo $prod->$modif;
	
	
?>