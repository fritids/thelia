<?php


include_once(realpath(dirname(__FILE__)) . "/config.php");
include_once("../../../classes/Commande.class.php");	
include_once("../../../fonctions/divers.php");

///////////////////test des variables envoyées par wexpay
$ref_order = (!empty($_POST['ref_order']))? $_POST['ref_order'] :  NULL;
$ref_wexpay =(!empty($_POST['ref_wexpay']))? $_POST['ref_wexpay'] :  NULL;
$amount = (!empty($_POST['amount']))? $_POST['amount'] : false;

if($ref_order!=NULL and $ref_wexpay!=NULL and $amount!=false){
   //////////////boucle pour trouver la reference dans le fichier xml securisé
   $paiement=false;
   //lecture du fichier
   $fp = fopen("https://$login:$pass@$urltransaction","r");
   while (!feof($fp)) { //parcourt de toutes les lignes
       $ligne = fgets($fp, 4096);            
		   $pos = strpos($ligne, "<RefTransaction>".$ref_wexpay."</RefTransaction>");        
		
		if ($pos !== false) {
           $paiement=true;
           break;
     	}    
   }
     if ($paiement) {

		$commande = new Commande();
		$commande->charger_trans($ref_order);
	    $commande->statut = 2;
	    $commande->genfact();
		$commande->maj();
   }

}

modules_fonction("confirmation", $commande);
?>
