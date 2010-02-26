<?php
include_once(realpath(dirname(__FILE__)) . "/../../classes/Administrateur.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../classes/Navigation.class.php");
session_start();
if( ! isset($_SESSION["util"]->id) ) {header("Location: ../index.php");exit;}
include_once(realpath(dirname(__FILE__)) . "/../../fonctions/divers.php");

include_once("../../classes/Produit.class.php");
include_once("../../classes/Produitdesc.class.php");
include_once("../../classes/Rubrique.class.php");
include_once("../../classes/Rubriquedesc.class.php");
	
	$sep = explode( "_", $_GET['id']);

	$pos = strpos($_GET['id'], "_");
	
	$modif = substr($_GET['id'], 0, $pos);
	
	if($modif == "titrerub"){
		$obj = new Rubrique();
		$obj->charger(substr($_GET['id'], $pos+1));	
		$objdesc = new Rubriquedesc();
		$objdesc->charger($obj->id);
		echo $objdesc->titre;
	} else if($modif == "titreprod") {	
		$obj = new Produit();
		$obj->charger(substr($_GET['id'], $pos+1));
		$objdesc = new Produitdesc();
		$objdesc->charger($obj->id);
		echo $objdesc->titre;		
	}
	else if($modif == "stock"){
		$obj = new Produit();
		$obj->charger(substr($_GET['id'], $pos+1));
		echo $obj->stock;
	}
	else if($modif == "prix"){
		$obj = new Produit();
		$obj->charger(substr($_GET['id'], $pos+1));
		echo $obj->prix;
	}
	else if($modif == "prix2"){
		$obj = new Produit();
		$obj->charger(substr($_GET['id'], $pos+1));
		echo $obj->prix2;
	}
	
	

?>