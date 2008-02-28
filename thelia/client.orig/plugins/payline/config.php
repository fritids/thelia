<?php

include_once(realpath(dirname(__FILE__)) . "/../../../classes/Variable.class.php");

$urlsite = new Variable();
$urlsite->charger("urlsite");

DEFINE( 'MERCHANT_ID', 'XXXXXXXX' ); // ID Marchand
DEFINE( 'ACCESS_KEY', 'XXXXXXXX' ); // Clé

DEFINE( 'PRODUCTION', TRUE); // Demonstration (FALSE) ou production (TRUE

DEFINE( 'PAYMENT_CURRENCY', 978 ); // devise (ex: 978 = EURO)
DEFINE( 'ORDER_CURRENCY', PAYMENT_CURRENCY );

DEFINE( 'SECURITY_MODE', 'SSL' ); // Protocole (ex: SSL = HTTPS)
DEFINE( 'LANGUAGE_CODE', 'FR' ); // Langue

DEFINE( 'PAYMENT_ACTION', 100 ); // Méthode paiement
DEFINE( 'PAYMENT_MODE', 'CPT' ); // Méthode de paiement par défaut

DEFINE('CANCEL_URL', $urlsite->valeur . '/regret.php');	// Adresse annulation
DEFINE('NOTIFICATION_URL', $urlsite->valeur . '/client/plugins/payline/confirmation.php');	// Adresse de notification auto
DEFINE('RETURN_URL', $urlsite->valeur . '/merci.php');	// Adresse de retour

DEFINE( 'CONTRACT_NUMBER', 'XXXXXXXX' ); // N° de contrat
DEFINE( 'CONTRACT_NUMBER_LIST', 'XXXXXXXX' ); // Idem

DEFINE( 'CUSTOM_PAYMENT_PAGE_CODE', '' );
?>