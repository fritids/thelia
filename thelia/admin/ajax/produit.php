<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
	include_once("../../classes/Produit.class.php");
	include_once("../../classes/Produitdesc.class.php");
	include_once("../../classes/Rubrique.class.php");
	include_once("../../classes/Rubriquedesc.class.php");

	$sep = explode( "_", $_POST['id']);

	$pos = strpos($_POST['id'], "_");
	
	$modif = substr($_POST['id'], 0, $pos);


	if($modif == "titrerub"){
		$obj = new Rubrique();
		$obj->charger(substr($_POST['id'], $pos+1));	
		$objdesc = new Rubriquedesc();
		$objdesc->charger($obj->id);
	} else {	
		$obj = new Produit();
		$obj->charger(substr($_POST['id'], $pos+1));
		$objdesc = new Produitdesc();
		$objdesc->charger($obj->id);		
	}	



	switch($modif){
		case 'prix' : $obj->prix = $_POST['value']; echo $obj->prix; break;
		case 'prix2' :  $obj->prix2 = $_POST['value']; echo $obj->prix2; break;
		case 'stock' :  $obj->stock = $_POST['value']; echo $obj->stock; break;
		case 'titreprod' :  $objdesc->titre = utf8_decode($_POST['value']); echo $objdesc->titre; break;
		case 'titrerub' :  $objdesc->titre = utf8_decode($_POST['value']); echo $objdesc->titre; break;
		case 'promo' : 
			if($obj->promo) $obj->promo = 0;
			else $obj->promo = 1;
			break;
		case 'ligne' : 
			if($obj->ligne) $obj->ligne = 0;
			else $obj->ligne= 1;
			break;
		case 'nouveaute' : 
			if($obj->nouveaute) $obj->nouveaute = 0;
			else $obj->nouveaute = 1;
			break;

	}

	$obj->maj();
	$objdesc->maj();
	
?>