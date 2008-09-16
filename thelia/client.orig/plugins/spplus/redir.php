<?php

/*
 * Aiguille l'internaute après paiement vers la page
 * merci.php si paiement OK ou
 * regret.php si paiement KO
 */

// pour utilisation de la fonction redirige()
// on charge divers.php
include_once("../../../fonctions/divers.php");
include_once("../../../classes/Variable.class.php");

$urlsite = new Variable();
$urlsite->charger("urlsite");

// paiement accepté, état = 1
// on redirige l'internaute vers merci.php

if($_GET['etat'] == "1") {
 redirige($urlsite->valeur . "/merci.php");
 }

// paiement refusé, état = 2
// on redirige l'internaute vers regret.php

if($_GET['etat'] == "2") {
 redirige($urlsite->valeur . "/regret.php");
 }

?>