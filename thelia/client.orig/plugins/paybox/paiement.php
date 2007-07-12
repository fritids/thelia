<?php

include_once("../../../classes/Navigation.class.php");
include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");

session_start();

$total = 0;

$total = $_SESSION['navig']->panier->total() + $_SESSION['navig']->commande->port;
$total -= $_SESSION['navig']->commande->remise;
$total = round($total, 2);
$total *= 100;

$trans =$_SESSION['navig']->commande->transaction;
 
$_SESSION['navig']->panier = new Panier();
$_SESSION['navig']->commande = new Commande();

$urlsite = new Variable();
$urlsite->charger("urlsite");

exec("./paiement $total " . $trans . " " . $_SESSION['navig']->client->email . " " . $urlsite->valeur . "/merci.php" . " " .  $urlsite->valeur . "/regret.php" . " " . $urlsite->valeur . "/regret.php", $tab);
header($tab[0]);
header($tab[1]);
header($tab[2]);

for($i=3;$i<count($tab); $i++)
	echo $tab[$i];
	
?>