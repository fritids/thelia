<?php

include_once(realpath(dirname(__FILE__)) . "/config.php");

//------------------------------------------------------------------------------------------------------------
// appel_spplus_php.php
// KIT SPPLUS : Page de test de l'interface de paiement avec API PHP
//---------------------------------------------------------------------------------------------------------
// Destinataire :		             Sites en intégration
// Auteur :				               Julien Bodin & Eric Duval
// Numéro Version :              1.10
// Date création	:	             25/08/2005
// Date dernière Modification :  25/08/2005
//---------------------------------------------------------------------------------------------------------
//
// Le script appel_spplus_php.php vous permettra de sécuriser l'appel au serveur de paiement SP PLUS.
// En effet, il permet d'appeler une des fonctions de calcul du sceau HMAC puis d'appliquer une signature
// numérique sur la chaîne des paramètres à envoyer au serveur de paiement SPPLUS.
//
// Ce script présente les fonctions qui permettent de calculer le sceau numérique HMAC à partir  
// de l'ensemble des paramètres passés au serveur de paiement SP PLUS.
//
//------------------------------------------------------------------------------------------------------------

//------------------------------------------------------------------------------------------------------------

	include_once("../../../classes/Navigation.class.php");	
	session_start();
	
	$total = $_SESSION['navig']->commande->total;

		
// INFORMATIONS A MODIFIER POUR CHAQUE COMMERCANT FOURNIES PAR LE SERVICE INTEGRATION SPPLUS
// cle marchand du commercant au format NT
   $clent = "$cle";

// code siret du commercant
   $codesiret = "$siret";

// Montant à récupérer du panier
   $montant="$total";

// Devise dans laquelle est exprimé la commande : 978 Code pour l'EURO
   $devise="978";

// Référence de la commande pour le commercant : unique pour chaque paiement effectué, limitée à 20 caractères
  // $reference = "spp" . date("YmdHis");
	$reference = $_SESSION['navig']->commande->transaction;
	
// L'email de l'internaute : élément fortement conseillé pour identification internaute
   $email=$_SESSION['navig']->client->email;
   
// Langue choisie pour l'interface de paiement
   $langue="FR";

// Taxe appliquée
   $taxe="0.0";

// Moyen de paiement choisi
   $moyen="CBS";
   
// Modalité de paiement choisie
   $modalite="1x";

// la fonction ci dessous permet de charger dynamiquement la librairie SP PLUS si elle n'est pas déclarée dans le fichier php.ini (rubrique extensions)
   dl('php_spplus.so');
   if (!extension_loaded('SPPLUS')) { echo "extension SP PLUS non chargée<br><br>\n"; }

// Fonction de calcul calcul_hmac
   $calcul_hmac=calcul_hmac($clent,$codesiret,$reference,$langue,$devise,$montant,$taxe,$validite);
   $url_calcul_hmac = "https://www.spplus.net/IPaiement/initialiserPaiement.do?siret=$codesiret&reference=$reference&langue=$langue&devise=$devise&montant=$montant&taxe=$taxe&hmac=$calcul_hmac&moyen=$moyen&modalite=$modalite";

// Fonction de calcul calculhmac
   $data="siret=$codesiret&reference=$reference&langue=$langue&devise=$devise&montant=$montant&taxe=$taxe&moyen=$moyen&modalite=$modalite";
   $calculhmac=calculhmac($clent,$data);
   $url_calculhmac = "https://www.spplus.net/IPaiement/initialiserPaiement.do?siret=$codesiret&reference=$reference&langue=$langue&devise=$devise&montant=$montant&taxe=$taxe&moyen=$moyen&modalite=$modalite&hmac=$calculhmac";

// Fonction de calcul nthmac
   $data= "$codesiret$reference$langue$devise$montant$taxe$moyen$modalite";
   $nthmac=nthmac($clent,$data);
   $url_nthmac = "https://www.spplus.net/IPaiement/initialiserPaiement.do?siret=$codesiret&reference=$reference&langue=$langue&devise=$devise&montant=$montant&taxe=$taxe&moyen=$moyen&modalite=$modalite&hmac=$nthmac";

// Fonction d'encryptage de l'url SigneUrlPaiement
// Cryptage en base 64 de la chaîne de paramètres à envoyer au serveur SPPLUS
   $url_signeurlpaiement = "https://www.spplus.net/IPaiement/initialiserPaiement.do?siret=$codesiret&reference=$reference&langue=$langue&devise=$devise&montant=$montant&taxe=$taxe&moyen=$moyen&modalite=$modalite";
   $urlspplus=signeurlpaiement($clent,$url_signeurlpaiement);

//------------------------------------------------------------------------------------------------------------
	//$_SESSION['navig']->panier = new Panier();
	//$_SESSION['navig']->commande = new Commande();
	
?>
<?php header("Location: $urlspplus"); ?>
