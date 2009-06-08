<?php
include_once("pre.php");
include_once("auth.php");

include_once("../classes/Produit.class.php");
include_once("../classes/Produitdesc.class.php");

$produit = new Produit();
if($produit->charger($ref)){
	$proddesc = new Produitdesc();
	$proddesc->charger($produit->id);
	$prix = $produit->prix;
	if($produit->promo) $prix = $produit->prix2;
	echo $proddesc->titre."|".$prix."|".$produit->tva;
}
else{
	echo ""."|".""."|"."";
}

?>